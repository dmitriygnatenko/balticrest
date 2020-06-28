<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Entity\Interfaces\PointDataFieldsInterface as PointFields;
use App\Entity\Interfaces\PointLangDataFieldsInterface as PointLangFields;
use App\Entity\PointLangData;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use App\Entity\Point;
use App\Entity\City;

class MapDataManager implements MapDataManagerInterface
{
    /** @var int */
    const ICON_IMAGE_WIDTH = 27;

    /** @var int */
    const ICON_IMAGE_HEIGHT = 48;

    /** @var LoggerInterface */
    private $logger;

    /** @var TranslatorInterface */
    private $translator;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var TagAwareCacheInterface */
    private $cache;

    /** @var CityManager */
    private $cityManager;

    /** @var PointManager */
    private $pointManager;

    /**
     * @param LoggerInterface $logger
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param TagAwareCacheInterface $cache
     * @param CityManagerInterface $cityManager
     * @param PointManagerInterface $pointManager
     */
    public function __construct(
        LoggerInterface $logger,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        TagAwareCacheInterface $cache,
        CityManagerInterface $cityManager,
        PointManagerInterface $pointManager
    )
    {
        $this->logger = $logger;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->cache = $cache;
        $this->cityManager = $cityManager;
        $this->pointManager = $pointManager;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapJsonData(Request $request): string
    {
        return $this->getCachedCityMapJsonData($request);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getCacheKey(Request $request): string
    {
        return self::CACHE_KEY . $request->get('city', '') . $request->get('category', '') . $request->getLocale();
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getCachedCityMapJsonData(Request $request): string
    {
        try {
            return $this->cache->get($this->getCacheKey($request), function (ItemInterface $item) use ($request) {
                $item->tag($request->get('city', ''));
                $item->expiresAfter(self::CACHE_EXPIRE_TIME);

                return $this->getCityMapData($request);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getCityMapData($request);
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getCityMapData(Request $request): string
    {
        $city = $request->get('city', '');
        $category = $request->get('category', '');
        $locale = $request->getLocale();

        $cities = $this->cityManager->getCachedActiveCities();

        $listDTO = new MapPointsListDTO();
        $listDTO->setTransPointButton($this->translator->trans('map.point.btn_title'));

        // Центр карты и масштаб
        if (isset($cities[$city])) {
            /** @var City $cityEntity */
            $cityEntity = $cities[$city];

            $listDTO->setCenterLat($cityEntity->getLat())
                ->setCenterLon($cityEntity->getLon())
                ->setZoom($cityEntity->getZoom());
        }

        // Объекты
        $points = $this->pointManager->getPointsByCityAndCategory($city, $category);

        if (!empty($points)) {
            foreach ($points as $point) {
                /** @var Point $point */

                /** @var PointLangData|null $pointLangData */
                $pointLangData = null;

                if ($locale == 'ru') {
                    // Данные на русском всегда присутствуют, не проверяем другие языки
                    $result = $point->getPointLangData()->filter(function($item) {
                        /** @var PointLangData $item */
                        return $item->getLanguage()->getCode() == 'ru';
                    });

                    if (!$result->isEmpty()) {
                        $pointLangData = $result->first();
                    }
                } else if ($locale == 'en') {
                    // Данные на английском всегда присутствуют, не проверяем другие языки
                    $result = $point->getPointLangData()->filter(function($item) {
                        /** @var PointLangData $item */
                        return $item->getLanguage()->getCode() == 'en';
                    });

                    if (!$result->isEmpty()) {
                        $pointLangData = $result->first();
                    }
                } else {
                    // Вначале проверяем данные на запрошенном языке
                    // Если они отсутствуют то берем английскую версию
                    $pointLangDataCollection = $point->getPointLangData();

                    $result = $pointLangDataCollection->filter(function($item) use ($locale) {
                        /** @var PointLangData $item */
                        return $item->getLanguage()->getCode() == $locale;
                    });

                    if (!$result->isEmpty()) {
                        $pointLangData = $result->first();

                        if (trim($pointLangData->getTitle()) == '') {
                            $pointLangData = null;
                        }
                    }

                    if ($pointLangData === null) {
                        $result = $pointLangDataCollection->filter(function($item) {
                            /** @var PointLangData $item */
                            return $item->getLanguage()->getCode() == 'en';
                        });

                        if (!$result->isEmpty()) {
                            $pointLangData = $result->first();
                        }
                    }
                }

                if ($pointLangData !== null) {
                    $pointData = $pointLangData->getData();

                    $link = $this->urlGenerator->generate(
                        'point',
                        [
                            '_locale' => $locale,
                            'city' => $city,
                            'category' => $point->getType()->getCode(),
                            'url' => $point->getUrl(),
                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    );

                    $pointDTO = (new MapPointDTO())
                        ->setLat($point->getLat())
                        ->setLon($point->getLon())
                        ->setTitle($pointLangData->getTitle())
                        ->setHint($pointLangData->getTitle())
                        ->setDescription($pointData[PointLangFields::FIELD_SHORT_DESC] ?? '')
                        ->setLink($link)
                        ->setImage($this->getPointImage($point))
                        ->setIconImage($this->getPointIconImage($point))
                        ->setIconImageWidth(self::ICON_IMAGE_WIDTH)
                        ->setIconImageHeight(self::ICON_IMAGE_HEIGHT);

                    $listDTO->addPoint($pointDTO);
                }
            }
        }

        return $listDTO->getJsonResult();
    }

    /**
     * @param Point $point
     *
     * @return string
     */
    private function getPointIconImage(Point $point): string
    {
        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
           return '/static/balticrest/images/markers/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return '/static/balticrest/images/markers/'  . $point->getType()->getCode() . '.png';
    }

    /**
     * @param Point $point
     *
     * @return string
     */
    private function getPointImage(Point $point): string
    {
        $pointLogo = $point->getLogo();

        if ($pointLogo) {
            return $pointLogo;
        }

        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
            return '/static/balticrest/images/logo/default/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return '/static/balticrest/images/logo/default/'  . $point->getType()->getCode() . '.png';
    }
}
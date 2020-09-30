<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Components\Balticrest\Service\Provider\CityDataProvider;
use App\Components\Balticrest\Service\Provider\CityDataProviderInterface;
use App\Components\Balticrest\Service\Provider\PointDataProviderInterface;
use App\Entity\Interfaces\PointLangDataFieldsInterface as PointLangFields;
use App\Entity\City;
use App\Entity\Point;
use App\Entity\PointLangData;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MapPointsListDTOMapper
{
    /** @var RequestStack */
    private $requestStack;

    /** @var TranslatorInterface */
    private $translator;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var CityDataProvider */
    private $cityDataProvider;

    /** @var PointDataProviderInterface */
    private $pointDataProvider;

    /**
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param CityDataProviderInterface $cityDataProvider
     * @param PointDataProviderInterface $pointDataProvider
     */
    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        CityDataProviderInterface $cityDataProvider,
        PointDataProviderInterface $pointDataProvider
    )
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->cityDataProvider = $cityDataProvider;
        $this->pointDataProvider = $pointDataProvider;
    }

    /**
     * @param string $city
     * @param array $points
     *
     * @return MapPointsListDTO
     */
    public function fill(string $city, array $points): MapPointsListDTO
    {
        $listDTO = new MapPointsListDTO();

        $locale = $this->requestStack->getMasterRequest()->getLocale();

        $listDTO->setTransPointButton($this->translator->trans('map.point.btn_title'));

        $cities = $this->cityDataProvider->getCachedCitiesList();

        if (isset($cities[$city])) {
            /** @var City $cityObject */
            $cityObject = $cities[$city];

            // Центр карты и масштаб
            $listDTO->setCenterLat($cityObject->getLat())
                ->setCenterLon($cityObject->getLon())
                ->setZoom($cityObject->getZoom());
        }

        // Объекты
        if (!empty($points)) {
            foreach ($points as $point) {
                /** @var Point $point */
                /** @var PointLangData|null $pointLangData */
                $pointLangData = null;

                if ($locale === 'ru') {
                    // Данные на русском всегда присутствуют, не проверяем другие языки
                    $result = $point->getPointLangData()->filter(static function($item) {
                        /** @var PointLangData $item */
                        return $item->getLanguage()->getCode() === 'ru';
                    });

                    if (!$result->isEmpty()) {
                        $pointLangData = $result->first();
                    }
                } else if ($locale === 'en') {
                    // Данные на английском всегда присутствуют, не проверяем другие языки
                    $result = $point->getPointLangData()->filter(static function($item) {
                        /** @var PointLangData $item */
                        return $item->getLanguage()->getCode() === 'en';
                    });

                    if (!$result->isEmpty()) {
                        $pointLangData = $result->first();
                    }
                } else {
                    // Вначале проверяем данные на запрошенном языке
                    // Если они отсутствуют то берем английскую версию
                    $pointLangDataCollection = $point->getPointLangData();

                    $result = $pointLangDataCollection->filter(static function($item) use ($locale) {
                        /** @var PointLangData $item */
                        return $item->getLanguage()->getCode() === $locale;
                    });

                    if (!$result->isEmpty()) {
                        $pointLangData = $result->first();

                        if (trim($pointLangData->getTitle()) === '') {
                            $pointLangData = null;
                        }
                    }

                    if ($pointLangData === null) {
                        $result = $pointLangDataCollection->filter(static function($item) {
                            /** @var PointLangData $item */
                            return $item->getLanguage()->getCode() === 'en';
                        });

                        if (!$result->isEmpty()) {
                            $pointLangData = $result->first();
                        }
                    }
                }

                if ($pointLangData !== null) {
                    $pointData = $pointLangData->getData();

                    if ($point->getUrl()) {
                        $link = $this->urlGenerator->generate(
                            'point',
                            [
                                '_locale' => $locale,
                                'city' => $point->getCity()->getCode(),
                                'category' => $point->getType()->getCode(),
                                'url' => $point->getUrl(),
                            ],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        );
                    } else {
                        $link = '';
                    }

                    $pointDTO = (new MapPointDTO())
                        ->setLat($point->getLat())
                        ->setLon($point->getLon())
                        ->setTitle($pointLangData->getTitle())
                        ->setHint($pointLangData->getTitle())
                        ->setDescription($pointData[PointLangFields::FIELD_SHORT_DESC] ?? '')
                        ->setLink($link)
                        ->setImage($this->pointDataProvider->getPointImage($point))
                        ->setIconImage($this->pointDataProvider->getPointIconImage($point))
                        ->setIconImageWidth(PointDataProviderInterface::ICON_IMAGE_WIDTH)
                        ->setIconImageHeight(PointDataProviderInterface::ICON_IMAGE_HEIGHT);

                    $listDTO->addPoint($pointDTO);
                }
            }
        }

        return $listDTO;
    }
}
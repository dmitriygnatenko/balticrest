<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Components\Balticrest\Service\Helper\MapPointHelper;
use App\Components\Balticrest\Service\Helper\MapPointHelperInterface;
use App\Components\Balticrest\Service\Provider\MapJsonDataProviderInterface;
use App\Components\Balticrest\Service\Provider\CityDataProvider;
use App\Components\Balticrest\Service\Provider\CityDataProviderInterface;
use App\Entity\City;
use App\Entity\Interfaces\PointLangDataFieldsInterface as PointLangFields;
use App\Entity\Point;
use App\Entity\PointLangData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MapPointsListDTOMapper
{
    /** @var TranslatorInterface */
    private $translator;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var CityDataProvider */
    private $cityDataProvider;

    /** @var MapPointHelper */
    private $mapPointHelper;

    /**
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param CityDataProviderInterface $cityManager
     * @param MapPointHelperInterface $mapPointHelper
     */
    public function __construct(
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        CityDataProviderInterface $cityManager,
        MapPointHelperInterface $mapPointHelper
    )
    {
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->cityDataProvider = $cityManager;
        $this->mapPointHelper = $mapPointHelper;
    }

    /**
     * @param MapPointsListDTO $listDTO
     * @param Request $request
     * @param array $points
     *
     * @return MapPointsListDTO
     */
    public function fill(MapPointsListDTO $listDTO, Request $request, array $points): MapPointsListDTO
    {
        $city = $request->get('city', '');
        $locale = $request->getLocale();

        $cities = $this->cityDataProvider->getCachedActiveCitiesList();

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

                    if ($point->getUrl()) {
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
                        ->setImage($this->mapPointHelper->getPointImage($point))
                        ->setIconImage($this->mapPointHelper->getPointIconImage($point))
                        ->setIconImageWidth(MapJsonDataProviderInterface::ICON_IMAGE_WIDTH)
                        ->setIconImageHeight(MapJsonDataProviderInterface::ICON_IMAGE_HEIGHT);

                    $listDTO->addPoint($pointDTO);
                }
            }
        }

        return $listDTO;
    }
}
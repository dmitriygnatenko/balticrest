<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Components\Balticrest\Service\Helper\MapPointHelper;
use App\Components\Balticrest\Service\Helper\MapPointHelperInterface;
use App\Components\Balticrest\Service\Provider\MapJsonDataProviderInterface;
use App\Entity\Interfaces\PointLangDataFieldsInterface as PointLangFields;
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

    /** @var MapPointHelper */
    private $mapPointHelper;

    /**
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param MapPointHelperInterface $mapPointHelper
     */
    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        MapPointHelperInterface $mapPointHelper
    )
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->mapPointHelper = $mapPointHelper;
    }

    /**
     * @param MapPointsListDTO $listDTO
     * @param array $points
     *
     * @return MapPointsListDTO
     */
    public function fill(MapPointsListDTO $listDTO, array $points): MapPointsListDTO
    {
        $locale = $this->requestStack->getMasterRequest()->getLocale();

        $listDTO->setTransPointButton($this->translator->trans('map.point.btn_title'));

        // Объекты
        if (!empty($points)) {
            /** @var Point $firstPoint */
            $firstPoint = $points[0];
            if ($firstPoint->getCity() !== null) {
                // Центр карты и масштаб
                $listDTO->setCenterLat($firstPoint->getCity()->getLat())
                    ->setCenterLon($firstPoint->getCity()->getLon())
                    ->setZoom($firstPoint->getCity()->getZoom());
            }

            foreach ($points as $point) {
                /** @var Point $point */
                /** @var PointLangData|null $pointLangData */
                $pointLangData = null;

                if ($locale === 'ru') {
                    // Данные на русском всегда присутствуют, не проверяем другие языки
                    $result = $point->getPointLangData()->filter(function($item) {
                        /** @var PointLangData $item */
                        return $item->getLanguage()->getCode() === 'ru';
                    });

                    if (!$result->isEmpty()) {
                        $pointLangData = $result->first();
                    }
                } else if ($locale === 'en') {
                    // Данные на английском всегда присутствуют, не проверяем другие языки
                    $result = $point->getPointLangData()->filter(function($item) {
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

                    $result = $pointLangDataCollection->filter(function($item) use ($locale) {
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
                        $result = $pointLangDataCollection->filter(function($item) {
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
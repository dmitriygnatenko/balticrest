<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Components\Balticrest\Service\Helper\PointImageHelperInterface;
use App\Components\Balticrest\Service\Helper\PointLangDataHelperInterface;
use App\Components\Balticrest\Service\Provider\CityDataProvider;
use App\Components\Balticrest\Service\Provider\CityDataProviderInterface;
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

    /** @var PointLangDataHelperInterface */
    private $pointLangDataHelper;

    /** @var PointImageHelperInterface */
    private $pointImageHelper;

    /**
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param CityDataProviderInterface $cityDataProvider
     * @param PointLangDataHelperInterface $pointLangDataHelper
     * @param PointImageHelperInterface $pointImageHelper
     */
    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        CityDataProviderInterface $cityDataProvider,
        PointLangDataHelperInterface $pointLangDataHelper,
        PointImageHelperInterface $pointImageHelper
    )
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->cityDataProvider = $cityDataProvider;
        $this->pointLangDataHelper = $pointLangDataHelper;
        $this->pointImageHelper = $pointImageHelper;
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

        $listDTO->setTransPointButton($this->translator->trans('map.point.btn_title', [], 'messages', $locale));

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
                $pointLangData = $this->pointLangDataHelper->getLangPointData($point, $locale);

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
                        ->setImage($this->pointImageHelper->getPointImage($point))
                        ->setIconImage($this->pointImageHelper->getPointIconImage($point))
                        ->setIconImageWidth($this->pointImageHelper->getIconImageWidth())
                        ->setIconImageHeight($this->pointImageHelper->getIconImageHeight());

                    $listDTO->addPoint($pointDTO);
                }
            }
        }

        return $listDTO;
    }
}
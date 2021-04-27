<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Components\Balticrest\Service\Helper\DataLanguageFilterInterface;
use App\Components\Balticrest\Service\Helper\PointImageHelperInterface;
use App\Components\Balticrest\Service\Provider\CityDataProviderInterface;
use App\Entity\Interfaces\PointLangDataFieldsInterface as PointLangFields;
use App\Entity\City;
use App\Entity\Language;
use App\Entity\Point;
use App\Entity\PointLangData;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MapPointsListDTOMapper
{
    private RequestStack $requestStack;

    private TranslatorInterface $translator;

    private UrlGeneratorInterface $urlGenerator;

    private CityDataProviderInterface $cityDataProvider;

    private DataLanguageFilterInterface $dataLanguageFilter;

    private PointImageHelperInterface $pointImageHelper;

    /**
     * @param RequestStack $requestStack
     * @param TranslatorInterface $translator
     * @param UrlGeneratorInterface $urlGenerator
     * @param CityDataProviderInterface $cityDataProvider
     * @param DataLanguageFilterInterface $dataLanguageFilter
     * @param PointImageHelperInterface $pointImageHelper
     */
    public function __construct(
        RequestStack $requestStack,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        CityDataProviderInterface $cityDataProvider,
        DataLanguageFilterInterface $dataLanguageFilter,
        PointImageHelperInterface $pointImageHelper
    ) {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
        $this->cityDataProvider = $cityDataProvider;
        $this->dataLanguageFilter = $dataLanguageFilter;
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

        $request = $this->requestStack->getMasterRequest();
        $locale = $request === null ? Language::DEFAULT_LANGUAGE : $request->getLocale();

        $listDTO->setTransPointButton($this->translator->trans('map.point.btn_title', [], 'messages', $locale));

        $cities = $this->cityDataProvider->getCachedCitiesList();

        if (isset($cities[$city])) {
            /** @var City $cityObject */
            $cityObject = $cities[$city];

            // Центр карты и масштаб
            $listDTO->setCenterLat((float) $cityObject->getLat())
                ->setCenterLon((float) $cityObject->getLon())
                ->setZoom($cityObject->getZoom());
        }

        // Объекты
        if (!empty($points)) {
            foreach ($points as $point) {
                /** @var Point $point */
                /** @var PointLangData|null $pointLangData */
                $pointLangData = $this->dataLanguageFilter->filter($point->getPointLangData(), $locale);

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

                    $lat = $point->getLat() ? (float) $point->getLat() : null;
                    $lon = $point->getLon() ? (float) $point->getLon() : null;

                    $pointDTO = (new MapPointDTO())
                        ->setLat($lat)
                        ->setLon($lon)
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
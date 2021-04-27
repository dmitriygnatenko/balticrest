<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\ListPointDTO;
use App\Components\Balticrest\Service\Helper\DataLanguageFilterInterface;
use App\Components\Balticrest\Service\Helper\PointImageHelperInterface;
use App\Entity\Interfaces\PointLangDataFieldsInterface as PointLangFields;
use App\Entity\Language;
use App\Entity\Point;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ListPointDTOMapper
{
    private RequestStack $requestStack;

    private UrlGeneratorInterface $urlGenerator;

    private DataLanguageFilterInterface $dataLanguageFilter;

    private PointImageHelperInterface $pointImageHelper;

    /**
     * @param RequestStack $requestStack
     * @param UrlGeneratorInterface $urlGenerator
     * @param DataLanguageFilterInterface $dataLanguageFilter
     * @param PointImageHelperInterface $pointImageHelper
     */
    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        DataLanguageFilterInterface $dataLanguageFilter,
        PointImageHelperInterface $pointImageHelper
    ) {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->dataLanguageFilter = $dataLanguageFilter;
        $this->pointImageHelper = $pointImageHelper;
    }

    /**
     * @param array $points
     *
     * @return array
     */
    public function fillAll(array $points): array
    {
        $dto = [];

        foreach ($points as $point) {
            $dto[] = $this->fill($point);
        }

        return $dto;
    }

    /**
     * @param Point $point
     *
     * @return ListPointDTO
     */
    public function fill(Point $point): ListPointDTO
    {
        $dto = new ListPointDTO();

        $request = $this->requestStack->getMasterRequest();
        $locale = $request === null ? Language::DEFAULT_LANGUAGE : $request->getLocale();

        $pointLangData = $this->dataLanguageFilter->filter($point->getPointLangData(), $locale);

        if ($pointLangData !== null) {
            $pointLangDataArray = $pointLangData->getData();

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

            $dto->setId($point->getId())
                ->setUrl($point->getUrl())
                ->setTitle($pointLangData->getTitle())
                ->setLink($link)
                ->setAddress($pointLangDataArray[PointLangFields::FIELD_ADDRESS] ?? '')
                ->setDescription($pointLangDataArray[PointLangFields::FIELD_SHORT_DESC] ?? '')
                ->setImage($this->pointImageHelper->getPointImage($point));
        }

        return $dto;
    }
}
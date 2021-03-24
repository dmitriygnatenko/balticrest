<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\Helper\DataLanguageFilterInterface;
use App\Components\Balticrest\Service\Helper\PointImageHelperInterface;
use App\Entity\Interfaces\PointDataFieldsInterface as Fields;
use App\Entity\Interfaces\PointLangDataFieldsInterface as LangFields;
use App\Components\Balticrest\Service\DTO\PointDTO;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Entity\Point;

class PointDTOMapper
{
    private RequestStack $requestStack;

    private UrlGeneratorInterface $urlGenerator;

    private TranslatorInterface $translator;

    private DataLanguageFilterInterface $dataLanguageFilter;

    private PointImageHelperInterface $pointImageHelper;

    /**
     * @param RequestStack $requestStack
     * @param UrlGeneratorInterface $urlGenerator
     * @param TranslatorInterface $translator
     * @param DataLanguageFilterInterface $dataLanguageFilter
     * @param PointImageHelperInterface $pointImageHelper
     */
    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator,
        DataLanguageFilterInterface $dataLanguageFilter,
        PointImageHelperInterface $pointImageHelper
    ) {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->translator = $translator;
        $this->dataLanguageFilter = $dataLanguageFilter;
        $this->pointImageHelper = $pointImageHelper;
    }

    /**
     * @param Point $point
     *
     * @return PointDTO
     */
    public function fill(Point $point): PointDTO
    {
        $dto = new PointDTO();

        $locale = $this->requestStack->getMasterRequest()->getLocale();

        $dto->setId($point->getId())
            ->setLat($point->getLat())
            ->setLon($point->getLon())
            ->setImage($this->pointImageHelper->getPointImage($point))
            ->setCity($point->getCity()->getCode())
            ->setCategory($point->getType()->getCode());

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

            $dto->setLink($link);
        }

        $pointData = $point->getData();

        $services = $pointData[Fields::FIELD_SERVICES] ?? [];

        $services = array_map(function ($service) use ($locale)  {
            return $this->translator->trans(
                'services.' . $service, [], 'messages', $locale
            );
        }, $services);

        $dto->setEmail($pointData[Fields::FIELD_EMAIl] ?? '')
            ->setWebsite($pointData[Fields::FIELD_WEBSITE] ?? '')
            ->setPhones($pointData[Fields::FIELD_PHONES] ?? '')
            ->setServices($services);

        $pointLangData = $this->dataLanguageFilter->filter($point->getPointLangData(), $locale);

        if ($pointLangData !== null) {
            $pointLangDataArray = $pointLangData->getData();

            $description = $pointLangDataArray[LangFields::FIELD_DESC] ?? '';
            $shortDescription = $pointLangDataArray[LangFields::FIELD_SHORT_DESC] ?? '';

            $dto->setTitle($pointLangData->getTitle())
                ->setDescription($description !== '' ? $description : $shortDescription)
                ->setAddress($pointLangDataArray[LangFields::FIELD_ADDRESS] ?? '');

            $address = $pointLangDataArray[LangFields::FIELD_ADDRESS] ?? '';

            if ($address) {
                $transCity = $this->translator->trans(
                    'cities.' . $point->getCity()->getCode(), [], 'messages', $locale
                );

                if ($locale === 'ru') {
                    $dto->setAddress($transCity . ', ' . $address);
                } else {
                    $dto->setAddress($address . ', ' . $transCity);
                }
            }
        }

        return $dto;
    }
}
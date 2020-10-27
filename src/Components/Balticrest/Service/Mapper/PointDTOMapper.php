<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\Helper\PointImageHelperInterface;
use App\Components\Balticrest\Service\Helper\PointLangDataHelperInterface;
use App\Entity\Interfaces\PointDataFieldsInterface as Fields;
use App\Entity\Interfaces\PointLangDataFieldsInterface as LangFields;
use App\Components\Balticrest\Service\DTO\PointDTO;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Point;

class PointDTOMapper
{
    /** @var RequestStack */
    private $requestStack;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var PointLangDataHelperInterface */
    private $pointLangDataHelper;

    /** @var PointImageHelperInterface */
    private $pointImageHelper;

    /**
     * @param RequestStack $requestStack
     * @param UrlGeneratorInterface $urlGenerator
     * @param PointLangDataHelperInterface $pointLangDataHelper
     * @param PointImageHelperInterface $pointImageHelper
     */
    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        PointLangDataHelperInterface $pointLangDataHelper,
        PointImageHelperInterface $pointImageHelper
    )
    {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->pointLangDataHelper = $pointLangDataHelper;
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
            ->setImage($this->pointImageHelper->getPointImage($point));

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

        $dto->setEmail($pointData[Fields::FIELD_EMAIl] ?? '')
            ->setWebsite($pointData[Fields::FIELD_WEBSITE] ?? '')
            ->setPhones($pointData[Fields::FIELD_PHONES] ?? '')
            ->setServices($pointData[Fields::FIELD_SERVICES] ?? []);

        $pointLangData = $this->pointLangDataHelper->getLangPointData($point, $locale);

        if ($pointLangData !== null) {
            $pointLangDataArray = $pointLangData->getData();

            $dto->setTitle($pointLangData->getTitle())
                ->setDescription($pointLangDataArray[LangFields::FIELD_DESC] ?? '')
                ->setAddress($pointLangDataArray[LangFields::FIELD_ADDRESS] ?? '');
        }

        return $dto;
    }
}
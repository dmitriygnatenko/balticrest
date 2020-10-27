<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Entity\Interfaces\PointDataFieldsInterface as Fields;
use App\Entity\Interfaces\PointLangDataFieldsInterface as LangFields;
use App\Components\Balticrest\Service\DTO\PointDTO;
use App\Components\Balticrest\Service\Provider\PointsDataProviderInterface;
use App\Entity\PointLangData;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Point;

class PointDTOMapper
{
    /** @var RequestStack */
    private $requestStack;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var PointsDataProviderInterface */
    private $pointsDataProvider;

    /**
     * @param RequestStack $requestStack
     * @param UrlGeneratorInterface $urlGenerator
     * @param PointsDataProviderInterface $pointsDataProvider
     */
    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        PointsDataProviderInterface $pointsDataProvider
    )
    {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->pointsDataProvider = $pointsDataProvider;
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
            ->setImage($this->pointsDataProvider->getPointImage($point));

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



        $pointLangData = null;

        $pointLangDataCollection = $point->getPointLangData();

        if ($locale === 'ru') {
            // Данные на русском всегда присутствуют, не проверяем другие языки
            $result = $pointLangDataCollection->filter(static function($item) {
                /** @var PointLangData $item */
                return $item->getLanguage()->getCode() === 'ru';
            });

            if (!$result->isEmpty()) {
                $pointLangData = $result->first();
            }
        } else if ($locale === 'en') {
            // Данные на английском всегда присутствуют, не проверяем другие языки
            $result =$pointLangDataCollection->filter(static function($item) {
                /** @var PointLangData $item */
                return $item->getLanguage()->getCode() === 'en';
            });

            if (!$result->isEmpty()) {
                $pointLangData = $result->first();
            }
        } else {
            // Вначале проверяем данные на запрошенном языке
            // Если они отсутствуют то берем английскую версию
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
            $pointLangDataArray = $pointLangData->getData();

            $dto->setTitle($pointLangData->getTitle())
                ->setDescription($pointLangDataArray[LangFields::FIELD_DESC] ?? '')
                ->setAddress($pointLangDataArray[LangFields::FIELD_ADDRESS] ?? '');
        }

        return $dto;
    }
}
<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mapper;

use App\Components\Balticrest\Service\DTO\ListPointDTO;
use App\Components\Balticrest\Service\Provider\PointDataProviderInterface;
use App\Entity\Interfaces\PointLangDataFieldsInterface as PointLangFields;
use App\Entity\Point;
use App\Entity\PointLangData;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ListPointDTOMapper
{
    /** @var RequestStack */
    private $requestStack;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var PointDataProviderInterface */
    private $pointDataProvider;

    /**
     * @param RequestStack $requestStack
     * @param UrlGeneratorInterface $urlGenerator
     * @param PointDataProviderInterface $pointDataProvider
     */
    public function __construct(
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        PointDataProviderInterface $pointDataProvider
    )
    {
        $this->requestStack = $requestStack;
        $this->urlGenerator = $urlGenerator;
        $this->pointDataProvider = $pointDataProvider;
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

        $locale = $this->requestStack->getMasterRequest()->getLocale();

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

            $dto->setTitle($pointLangData->getTitle())
                ->setLink($link)
                ->setDescription($pointLangDataArray[PointLangFields::FIELD_SHORT_DESC] ?? '')
                ->setImage($this->pointDataProvider->getPointImage($point));
        }

        return $dto;
    }
}
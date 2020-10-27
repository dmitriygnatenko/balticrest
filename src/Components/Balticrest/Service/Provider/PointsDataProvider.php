<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Entity\Interfaces\PointDataFieldsInterface as PointFields;
use App\Entity\PointLangData;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Point;

class PointsDataProvider implements PointsDataProviderInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsByCityAndCategory(string $city, string $category): array
    {
        return $this->em->getRepository(Point::class)->getPointsByCityAndCategory($city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsWithUrlByCityAndCategory(string $city, string $category): array
    {
        return $this->em->getRepository(Point::class)->getPointsWithUrlByCityAndCategory($city, $category);
    }

    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointImage(Point $point): string
    {
        $pointLogo = $point->getLogo();

        if ($pointLogo) {
            return $pointLogo;
        }

        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
            return self::IMAGES_PATH . 'logo/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return self::IMAGES_PATH . 'logo/'  . $point->getType()->getCode() . '.png';
    }

    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointIconImage(Point $point): string
    {
        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
            return self::IMAGES_PATH . 'markers/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return self::IMAGES_PATH . 'markers/'  . $point->getType()->getCode() . '.png';
    }

    /**
     * @param Point $point
     * @param string $locale
     *
     * @return PointLangData|null
     */
    public function getLangPointData(Point $point, string $locale): ?PointLangData
    {
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


    }

}
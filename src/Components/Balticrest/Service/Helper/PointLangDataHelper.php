<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use App\Entity\Point;
use App\Entity\PointLangData;

class PointLangDataHelper implements PointLangDataHelperInterface
{
    /**
     * @param Point $point
     * @param string $locale
     *
     * @return PointLangData|null
     */
    public function getLangPointData(Point $point, string $locale): ?PointLangData
    {
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

        return $pointLangData;
    }
}
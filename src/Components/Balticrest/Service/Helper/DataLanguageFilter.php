<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use App\Entity\Language;
use Doctrine\Common\Collections\Collection;

class DataLanguageFilter implements DataLanguageFilterInterface
{
    /**
     * @param Collection $collection
     * @param string $locale
     *
     * @return object|null
     */
    public function filter(Collection $collection, string $locale): ?object
    {
        $langData = null;

        if ($locale === Language::DEFAULT_LANGUAGE) {
            // Данные на русском всегда присутствуют, не проверяем другие языки
            $result = $collection->filter(static function($item) {
                return $item->getLanguage()->getCode() === Language::DEFAULT_LANGUAGE;
            });

            if (!$result->isEmpty()) {
                $langData = $result->first();
            }
        } else if ($locale === 'en') {
            // Данные на английском всегда присутствуют, не проверяем другие языки
            $result = $collection->filter(static function($item) {
                return $item->getLanguage()->getCode() === 'en';
            });

            if (!$result->isEmpty()) {
                $langData = $result->first();
            }
        } else {
            // Вначале проверяем данные на запрошенном языке
            // Если они отсутствуют то берем английскую версию
            $result = $collection->filter(static function($item) use ($locale) {
                return $item->getLanguage()->getCode() === $locale;
            });

            if (!$result->isEmpty()) {
                $langData = $result->first();
                if (trim($langData->getTitle()) === '') {
                    $langData = null;
                }
            }

            if ($langData === null) {
                $result = $collection->filter(static function($item) {
                    return $item->getLanguage()->getCode() === 'en';
                });

                if (!$result->isEmpty()) {
                    $langData = $result->first();
                }
            }
        }

        return $langData;
    }
}
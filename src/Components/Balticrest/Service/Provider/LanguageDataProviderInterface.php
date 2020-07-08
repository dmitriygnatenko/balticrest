<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface LanguageDataProviderInterface
{
    /**
     * @return array
     */
    public function getCachedActiveLanguagesList(): array;

    /**
     * @return array
     */
    public function getActiveLanguagesList(): array;
}
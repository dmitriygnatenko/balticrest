<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface LanguageDataProviderInterface
{
    /**
     * @return array
     */
    public function getCachedLanguagesList(): array;

    /**
     * @return array
     */
    public function getLanguagesList(): array;
}
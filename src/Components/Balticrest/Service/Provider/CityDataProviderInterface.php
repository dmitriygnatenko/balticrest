<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface CityDataProviderInterface
{
    /**
     * @return array
     */
    public function getCachedActiveCitiesList(): array;

    /**
     * @return array
     */
    public function getActiveCitiesList(): array;
}
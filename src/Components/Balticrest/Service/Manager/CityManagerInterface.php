<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

interface CityManagerInterface
{
    /** @var string */
    const CACHE_KEY = 'balticrest.city.active_cities';

    /** @var int Неделя */
    const CACHE_EXPIRE_TIME = 604800;

    /** @var string */
    const CACHE_TAG = 'cities';

    /**
     * @return array
     */
    public function getCachedActiveCities(): array;

    /**
     * @return array
     */
    public function getActiveCities(): array;
}
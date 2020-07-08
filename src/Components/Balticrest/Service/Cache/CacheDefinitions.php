<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

interface CacheDefinitions
{
    /** @var string */
    const CITY_LIST_CACHE_KEY = 'balticrest.active_cities_list';

    /** @var int */
    const CITY_LIST_CACHE_EXPIRE_TIME = 604800;

    /** @var string */
    const LANGUAGE_LIST_CACHE_KEY = 'balticrest.active_languages_list';

    /** @var int Неделя */
    const LANGUAGE_LIST_CACHE_EXPIRE_TIME = 604800;

    /** @var string */
    const POINT_TYPES_CACHE_KEY = 'balticrest.point_types';

    /** @var int */
    const POINT_TYPES_CACHE_EXPIRE_TIME = 604800;
}
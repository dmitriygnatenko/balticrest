<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

interface CacheKeyInterface
{
    /** @var string */
    public const KEY_POINT_TYPES = 'point_types';

    /** @var string */
    public const KEY_CITY_POINT_TYPES = 'city_point_types';

    /** @var string */
    public const KEY_CITIES = 'cities';

    /** @var string */
    public const KEY_LANGUAGES = 'languages';
}
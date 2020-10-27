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
    public const KEY_CITY_POINT_WITH_URL_TYPES = 'city_point_with_url_types';

    /** @var string */
    public const KEY_CITIES = 'cities';

    /** @var string */
    public const KEY_LANGUAGES = 'languages';

    /** @var string */
    public const KEY_MAP_POINTS = 'map_points';

    /** @var string */
    public const KEY_LIST_POINTS = 'list_points';

    /** @var string */
    public const KEY_ARTICLES = 'articles';

    /** @var string */
    public const KEY_POINTS = 'points';
}
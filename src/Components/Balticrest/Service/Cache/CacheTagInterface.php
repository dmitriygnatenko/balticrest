<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

interface CacheTagInterface
{
    /** @var string */
    public const TAG_ARTICLES = 'tag_articles';

    /** @var string */
    public const TAG_NEWS = 'tag_news';

    /** @var string */
    public const TAG_POINTS = 'tag_points';

    /** @var string */
    public const TAG_SCHEDULE = 'tag_schedule';
}
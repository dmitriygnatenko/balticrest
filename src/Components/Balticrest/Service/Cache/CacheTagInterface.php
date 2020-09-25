<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

interface CacheTagInterface
{
    /** @var string */
    public const TAG_ARTICLE = 'tag_article';

    /** @var string */
    public const TAG_NEWS = 'tag_news';
}
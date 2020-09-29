<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

interface CacheManagerInterface
{
    /**
     * @param string $tag
     *
     * @return void
     */
    public function clearByTag(string $tag): void;
}
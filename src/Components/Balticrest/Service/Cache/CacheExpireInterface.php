<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

interface CacheExpireInterface
{
    /** @var int */
    public const EXPIRE_DAY = 86400;

    /** @var int */
    public const EXPIRE_WEEK = 604800;
}
<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Psr\Log\LoggerInterface;

class CacheManager implements CacheManagerInterface, CacheDefinitions
{
    /** @var TagAwareCacheInterface */
    private $cache;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param TagAwareCacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(TagAwareCacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function clearAllCache(): void
    {
        try {
            $this->cache->delete(self::CITY_LIST_CACHE_KEY);
            $this->cache->delete(self::LANGUAGE_LIST_CACHE_KEY);
            $this->cache->delete(self::POINT_TYPES_CACHE_KEY);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
        }
    }
}
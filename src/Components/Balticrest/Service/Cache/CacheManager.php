<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Psr\Log\LoggerInterface;

class CacheManager implements CacheManagerInterface, CacheDefinitions, CacheTagInterface
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
     * @param string $tag
     *
     * @return void
     */
    public function clearByTag(string $tag): void
    {
        try {
            $this->cache->invalidateTags([$tag]);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
        }
    }

    /**
     * @param string $city
     * @param string $category
     * @param string $locale
     *
     * @return string
     */
    public function getMapPointsCacheKey(string $city, string $category, string $locale): string
    {
        return self::MAP_POINTS_CACHE_KEY . $city . $category . $locale;
    }

    /**
     * @param string $city
     *
     * @return string
     */
    public function getActivePointsCacheKey(string $city): string
    {
        return self::ACTIVE_POINT_TYPES_CACHE_KEY . $city;
    }
}
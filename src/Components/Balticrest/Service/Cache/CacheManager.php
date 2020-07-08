<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
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

            $this->cache->invalidateTags([self::MAP_POINTS_TAG]);
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getMapPointsCacheKey(Request $request): string
    {
        return self::MAP_POINTS_CACHE_KEY
            . $request->get('city', '')
            . $request->get('category', '')
            . $request->getLocale();
    }
}
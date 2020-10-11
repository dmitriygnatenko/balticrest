<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Cache;

use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Psr\Log\LoggerInterface;

class CacheManager implements CacheManagerInterface, CacheTagInterface, CacheKeyInterface
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
     *
     * @return string
     */
    public function getMapPointTypesCacheKey(string $city): string
    {
        return self::KEY_CITY_POINT_TYPES . $city;
    }

    /**
     * @param string $city
     *
     * @return string
     */
    public function getListPointTypesCacheKey(string $city): string
    {
        return self::KEY_CITY_POINT_WITH_URL_TYPES . $city;
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
        return self::KEY_MAP_POINTS . $city . $category . $locale;
    }

    /**
     * @param string $url
     * @param string $locale
     *
     * @return string
     */
    public function getArticleCacheKey(string $url, string $locale): string
    {
        return self::KEY_ARTICLES . $url . $locale;
    }
}
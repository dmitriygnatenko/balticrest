<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheDefinitions;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Entity\PointType;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class PointTypeProvider implements PointTypeProviderInterface, CacheDefinitions
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var TagAwareCacheInterface */
    private $cache;

    /** @var CacheManager */
    private $cacheManager;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        EntityManagerInterface $em,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getCachedPointTypes(): array
    {
        try {
            return $this->cache->get(self::POINT_TYPES_CACHE_KEY, function (ItemInterface $item) {
                $item->expiresAfter(self::POINT_TYPES_CACHE_EXPIRE_TIME);
                return $this->getPointTypes();
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
            return $this->getPointTypes();
        }
    }

    /**
     * @return array
     */
    public function getPointTypes(): array
    {
        return $this->em->getRepository(PointType::class)->findAll();
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getCachedActivePointTypesList(string $city): array
    {
        $cacheKey = $this->cacheManager->getActivePointsCacheKey($city);

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($city) {
                $item->expiresAfter(self::ACTIVE_POINT_TYPES_CACHE_EXPIRE_TIME);
                $item->tag([$city]);
                return $this->getActivePointTypesList($city);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
            return $this->getActivePointTypesList($city);
        }
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getActivePointTypesList(string $city): array
    {
        $formattedResults = [];

        $results = $this->em->getRepository(PointType::class)->findActivePointTypes($city);

        foreach ($results as $result) {
            $formattedResults[$result->getCode()] = true;
        }

        return $formattedResults;
    }
}
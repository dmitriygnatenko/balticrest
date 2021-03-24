<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheKeyInterface;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Repository\PointTypeRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class PointTypeProvider implements PointTypeProviderInterface
{
    private TagAwareCacheInterface $cache;

    private CacheManagerInterface $cacheManager;

    private LoggerInterface $logger;

    private PointTypeRepository $pointTypeRepository;

    /**
     * @param PointTypeRepository $pointTypeRepository
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        PointTypeRepository $pointTypeRepository,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        LoggerInterface $logger
    ) {
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->logger = $logger;
        $this->pointTypeRepository = $pointTypeRepository;
    }

    /**
     * @return array
     */
    public function getCachedPointTypes(): array
    {
        try {
            return $this->cache->get(CacheKeyInterface::KEY_POINT_TYPES, function (ItemInterface $item) {
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);

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
        return $this->pointTypeRepository->findAll();
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getCachedMapPointTypesList(string $city): array
    {
        $cacheKey = $this->cacheManager->getMapPointTypesCacheKey($city);

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($city) {
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);
                $item->tag([$city, CacheTagInterface::TAG_POINTS]);

                return $this->getMapPointTypesList($city);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getMapPointTypesList($city);
        }
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getMapPointTypesList(string $city): array
    {
        $formattedResults = [];

        $results = $this->pointTypeRepository->getCityPointTypes($city);

        foreach ($results as $result) {
            $formattedResults[$result->getCode()] = true;
        }

        return $formattedResults;
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getCachedListPointTypesList(string $city): array
    {
        $cacheKey = $this->cacheManager->getListPointTypesCacheKey($city);

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($city) {
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);
                $item->tag([$city, CacheTagInterface::TAG_POINTS]);

                return $this->getListPointTypesList($city);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getListPointTypesList($city);
        }
    }

    /**
     * @param string $city
     *
     * @return array
     */
    public function getListPointTypesList(string $city): array
    {
        $formattedResults = [];

        $results = $this->pointTypeRepository->getCityPointWithUrlsTypes($city);

        foreach ($results as $result) {
            $formattedResults[$result->getCode()] = true;
        }

        return $formattedResults;
    }
}
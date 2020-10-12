<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\Mapper\MapPointsListDTOMapper;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class MapDataProvider implements MapDataProviderInterface
{
    /** @var RequestStack */
    private $requestStack;

    /** @var LoggerInterface */
    private $logger;

    /** @var TagAwareCacheInterface */
    private $cache;

    /** @var CacheManager */
    private $cacheManager;

    /** @var PointDataProvider */
    private $pointDataProvider;

    /** @var MapPointsListDTOMapper */
    private $mapPointsListDTOMapper;

    /**
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param PointDataProviderInterface $pointManager
     * @param MapPointsListDTOMapper $mapPointsListDTOMapper
     */
    public function __construct(
        RequestStack $requestStack,
        LoggerInterface $logger,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        PointDataProviderInterface $pointManager,
        MapPointsListDTOMapper $mapPointsListDTOMapper
    )
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->pointDataProvider = $pointManager;
        $this->mapPointsListDTOMapper = $mapPointsListDTOMapper;
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getCachedData(string $city, string $category = ''): string
    {
        $cacheKey = $this->cacheManager->getMapPointsCacheKey(
            $city, $category, $this->requestStack->getMasterRequest()->getLocale()
        );

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($city, $category) {
                $item->tag([$city, CacheTagInterface::TAG_POINTS]);
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);

                return $this->getData($city, $category);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getData($city, $category);
        }
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getData(string $city, string $category = ''): string
    {
        $points = $this->pointDataProvider->getPointsByCityAndCategory($city, $category);

        $dto = $this->mapPointsListDTOMapper->fill($city, $points);

        return $dto->getJsonResult();
    }
}
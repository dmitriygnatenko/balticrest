<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheDefinitions;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Components\Balticrest\Service\Mapper\MapPointsListDTOMapper;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class MapJsonJsonDataProvider implements MapJsonDataProviderInterface, CacheDefinitions
{
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
     * @param LoggerInterface $logger
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param PointDataProviderInterface $pointManager
     * @param MapPointsListDTOMapper $mapPointsListDTOMapper
     */
    public function __construct(
        LoggerInterface $logger,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        PointDataProviderInterface $pointManager,
        MapPointsListDTOMapper $mapPointsListDTOMapper
    )
    {
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->pointDataProvider = $pointManager;
        $this->mapPointsListDTOMapper = $mapPointsListDTOMapper;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapJsonData(Request $request): string
    {
        return $this->getCachedCityMapJsonData($request);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getCachedCityMapJsonData(Request $request): string
    {
        $cacheKey = $this->cacheManager->getMapPointsCacheKey($request);

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($request) {
                $item->tag([$request->get('city', ''), self::MAP_POINTS_TAG]);
                $item->expiresAfter(self::MAP_POINTS_CACHE_EXPIRE_TIME);

                return $this->getCityMapJsonData($request);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
            return $this->getCityMapJsonData($request);
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    private function getCityMapJsonData(Request $request): string
    {
        $city = $request->get('city', '');
        $category = $request->get('category', '');

        $points = $this->pointDataProvider->getPointsByCityAndCategory($city, $category);

        $dto = $this->mapPointsListDTOMapper->fill(new MapPointsListDTO(), $request, $points);

        return $dto->getJsonResult();
    }
}
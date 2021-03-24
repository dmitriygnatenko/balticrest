<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\DTO\PointDTO;
use App\Components\Balticrest\Service\Mapper\PointDTOMapper;
use App\Repository\PointRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PointDataProvider implements PointDataProviderInterface
{
    private RequestStack $requestStack;

    private LoggerInterface $logger;

    private TagAwareCacheInterface $cache;

    private CacheManagerInterface $cacheManager;

    private PointDTOMapper $pointDTOMapper;

    private PointRepository $pointRepository;

    /**
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     * @param PointRepository $pointRepository
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param PointDTOMapper $pointDTOMapper
     */
    public function __construct(
        RequestStack $requestStack,
        LoggerInterface $logger,
        PointRepository $pointRepository,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        PointDTOMapper $pointDTOMapper
    ) {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->pointDTOMapper = $pointDTOMapper;
        $this->pointRepository = $pointRepository;
    }

    /**
     * @param string $url
     *
     * @return PointDTO|null
     */
    public function getCachedPointData(string $url): ?PointDTO
    {
        $cacheKey = $this->cacheManager->getPointCacheKey(
            $url, $this->requestStack->getMasterRequest()->getLocale()
        );

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($url) {
                $item->tag([CacheTagInterface::TAG_POINTS]);
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);

                return $this->getPointData($url);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getPointData($url);
        }
    }

    /**
     * @param string $url
     *
     * @return PointDTO|null
     */
    public function getPointData(string $url): ?PointDTO
    {
        $point = $this->pointRepository->findOneBy(['url' => $url, 'is_active' => true]);

        return $point === null ? null : $this->pointDTOMapper->fill($point);
    }
}
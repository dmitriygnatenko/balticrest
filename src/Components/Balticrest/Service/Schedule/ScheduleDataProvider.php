<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Schedule;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\DTO\ScheduleListDTO;
use App\Components\Balticrest\Service\Mapper\ScheduleListDTOMapper;
use App\Repository\ScheduleRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ScheduleDataProvider implements ScheduleDataProviderInterface, StationsInterface
{
    private ScheduleRepository $scheduleRepository;

    private ScheduleListDTOMapper $scheduleListDTOMapper;

    private TagAwareCacheInterface $cache;

    private CacheManagerInterface $cacheManager;

    private RequestStack $requestStack;

    private LoggerInterface $logger;

    /**
     * @param ScheduleRepository $scheduleRepository
     * @param ScheduleListDTOMapper $scheduleListDTOMapper
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     */
    public function __construct(
        ScheduleRepository $scheduleRepository,
        ScheduleListDTOMapper $scheduleListDTOMapper,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        RequestStack $requestStack,
        LoggerInterface $logger
    )
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->scheduleListDTOMapper = $scheduleListDTOMapper;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * @param string $url
     *
     * @return ScheduleListDTO[]|null
     */
    public function getCachedPointData(string $url): ?array
    {
        $cacheKey = $this->cacheManager->getScheduleCacheKey(
            $url, $this->requestStack->getMasterRequest()->getLocale()
        );

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($url) {
                $item->tag([CacheTagInterface::TAG_SCHEDULE]);
                $item->expiresAfter(CacheExpireInterface::EXPIRE_12_HOURS);

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
     * @return ScheduleListDTO[]|null
     */
    public function getPointData(string $url): ?array
    {
        $stationId = $this->getMappedStationId($url);
        if ($stationId === null) {
            return null;
        }

        $schedules = $this->scheduleRepository->findAllByStationId($stationId);
        if (empty($schedules)) {
            return null;
        }

        return $this->scheduleListDTOMapper->fillAll($schedules);
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    private function getMappedStationId(string $url): ?string
    {
        return self::STATIONS_MAPPING[$url] ?? null;
    }
}
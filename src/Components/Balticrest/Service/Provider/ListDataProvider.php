<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\Mapper\ListPointDTOMapper;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ListDataProvider implements ListDataProviderInterface
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

    /** @var ListPointDTOMapper */
    private $pointsListDTOMapper;

    /**
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param PointDataProviderInterface $pointManager
     * @param ListPointDTOMapper $pointsListDTOMapper
     */
    public function __construct(
        RequestStack $requestStack,
        LoggerInterface $logger,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        PointDataProviderInterface $pointManager,
        ListPointDTOMapper $pointsListDTOMapper
    )
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->pointDataProvider = $pointManager;
        $this->pointsListDTOMapper = $pointsListDTOMapper;
    }

    /**
     * @inheritDoc
     */
    public function getCachedData(string $city, string $category): array
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
     * @inheritDoc
     */
    public function getData(string $city, string $category): array
    {
        $points = $this->pointDataProvider->getPointsWithUrlByCityAndCategory($city, $category);

        $dtoList = $this->pointsListDTOMapper->fillAll($points);

        return $this->sort($dtoList);
    }

    /**
     * @param array $dtoList
     *
     * @return array
     */
    private function sort(array $dtoList): array
    {
        usort($dtoList, static function ($a, $b) {
            $aTitle = $a->getTitle();
            $bTitle = $b->getTitle();

            if ($aTitle === $bTitle) {
                return 0;
            }

            return ($aTitle < $bTitle) ? -1 : 1;
        });

        return $dtoList;
    }
}
<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\DTO\ListPointDTO;
use App\Components\Balticrest\Service\DTO\PointDTO;
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

    /** @var PointsDataProvider */
    private $pointsDataProvider;

    /** @var ListPointDTOMapper */
    private $pointsListDTOMapper;

    /**
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param PointsDataProviderInterface $pointsDataProvider
     * @param ListPointDTOMapper $pointsListDTOMapper
     */
    public function __construct(
        RequestStack $requestStack,
        LoggerInterface $logger,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        PointsDataProviderInterface $pointsDataProvider,
        ListPointDTOMapper $pointsListDTOMapper
    )
    {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->pointsDataProvider = $pointsDataProvider;
        $this->pointsListDTOMapper = $pointsListDTOMapper;
    }

    /**
     * @inheritDoc
     */
    public function getCachedData(string $city, string $category): array
    {
        $cacheKey = $this->cacheManager->getListPointsCacheKey(
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
        $points = $this->pointsDataProvider->getPointsWithUrlByCityAndCategory($city, $category);

        $dtoList = $this->pointsListDTOMapper->fillAll($points);

        return $this->sort($dtoList);
    }

    /**
     * @param PointDTO $point
     *
     * @return array
     */
    public function getCachedSimilarPointsData(PointDTO $point): array
    {
        $list = $this->getCachedData($point->getCity(), $point->getCategory());

        return $this->getSimilarPointsFromList($list, $point);
    }

    /**
     * @param PointDTO $point
     *
     * @return array
     */
    public function getSimilarPointsData(PointDTO $point): array
    {
        $list = $this->getData($point->getCity(), $point->getCategory());

        return $this->getSimilarPointsFromList($list, $point);
    }

    /**
     * @param array $list
     * @param PointDTO $point
     *
     * @return array
     */
    private function getSimilarPointsFromList(array $list, PointDTO $point): array
    {
        $excludedId = $point->getId();

        $list = array_filter($list, static function ($elem) use ($excludedId) {
            /** @var $elem ListPointDTO */
            return $elem->getId() !== $excludedId;
        });

        return array_slice($list, 0, self::SIMILAR_POINTS_COUNT);
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
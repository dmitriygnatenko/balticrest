<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\DTO\PointDTO;
use App\Components\Balticrest\Service\Mapper\PointDTOMapper;
use Psr\Cache\InvalidArgumentException;
use App\Entity\Point;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class PointDataProvider implements PointDataProviderInterface
{
    /** @var RequestStack */
    private $requestStack;

    /** @var LoggerInterface */
    private $logger;

    /** @var EntityManagerInterface */
    private $em;

    /** @var TagAwareCacheInterface */
    private $cache;

    /** @var CacheManager */
    private $cacheManager;

    /** @var PointDTOMapper */
    private $pointDTOMapper;

    /**
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param PointDTOMapper $pointDTOMapper
     */
    public function __construct(
        RequestStack $requestStack,
        LoggerInterface $logger,
        EntityManagerInterface $em,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        PointDTOMapper $pointDTOMapper
    ) {
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->em = $em;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->pointDTOMapper = $pointDTOMapper;
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
        $point = $this->em->getRepository(Point::class)->findOneBy(['url' => $url, 'is_active' => true]);

        return $point === null ? null : $this->pointDTOMapper->fill($point);
    }
}
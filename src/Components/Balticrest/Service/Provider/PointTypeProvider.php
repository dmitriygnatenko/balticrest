<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheDefinitions;
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

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param EntityManagerInterface $em
     * @param TagAwareCacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $em, TagAwareCacheInterface $cache, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->cache = $cache;
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
}
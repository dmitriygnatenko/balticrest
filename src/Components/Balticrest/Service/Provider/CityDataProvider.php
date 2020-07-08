<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheDefinitions;
use App\Entity\City;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CityDataProvider implements CityDataProviderInterface, CacheDefinitions
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
    public function getCachedActiveCitiesList(): array
    {
        try {
            return $this->cache->get(self::CITY_LIST_CACHE_KEY, function (ItemInterface $item) {
                $item->expiresAfter(self::CITY_LIST_CACHE_EXPIRE_TIME);
                return $this->getActiveCitiesList();
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
            return $this->getActiveCitiesList();
        }
    }

    /**
     * @return array
     */
    public function getActiveCitiesList(): array
    {
        $results = $this->em->getRepository(City::class)
            ->findBy(['is_active' => true], ['id' => 'ASC']);

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[$result->getCode()] = $result;
        }

        return $formattedResults;
    }
}
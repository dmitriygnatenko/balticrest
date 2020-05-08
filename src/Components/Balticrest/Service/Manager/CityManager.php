<?php

namespace App\Components\Balticrest\Service\Manager;

use App\Entity\City;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CityManager implements CityManagerInterface
{
    /** @var string */
    const CACHE_KEY = 'balticrest.city.active_cities';

    /** @var int */
    const CACHE_EXPIRE_TIME = 86400;

    /** @var EntityManagerInterface */
    private $em;

    /** @var CacheInterface */
    private $cache;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param EntityManagerInterface $em
     * @param CacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(EntityManagerInterface $em, CacheInterface $cache, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->cache = $cache;
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getActiveCached(): array
    {
        try {
            return $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
                $item->expiresAfter(self::CACHE_EXPIRE_TIME);
                return $this->getActive();
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
            return $this->getActive();
        }
    }

    /**
     * @return array
     */
    public function getActive(): array
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
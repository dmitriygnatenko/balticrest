<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheKeyInterface;
use App\Entity\City;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CityDataProvider implements CityDataProviderInterface
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
    public function getCachedCitiesList(): array
    {
        try {
            return $this->cache->get(CacheKeyInterface::KEY_CITIES, function (ItemInterface $item) {
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);

                return $this->getCitiesList();
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getCitiesList();
        }
    }

    /**
     * @return array
     */
    public function getCitiesList(): array
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
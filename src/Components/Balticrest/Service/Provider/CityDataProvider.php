<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheKeyInterface;
use App\Repository\CityRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CityDataProvider implements CityDataProviderInterface
{
    private TagAwareCacheInterface $cache;

    private LoggerInterface $logger;

    private CityRepository $cityRepository;

    /**
     * @param CityRepository $cityRepository
     * @param TagAwareCacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(CityRepository $cityRepository, TagAwareCacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
        $this->cityRepository = $cityRepository;
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
        $results = $this->cityRepository->findBy(['is_active' => true], ['id' => 'ASC']);

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[$result->getCode()] = $result;
        }

        return $formattedResults;
    }
}
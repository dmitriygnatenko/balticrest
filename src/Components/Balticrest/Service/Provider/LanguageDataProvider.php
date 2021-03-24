<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheKeyInterface;
use App\Repository\LanguageRepository;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class LanguageDataProvider implements LanguageDataProviderInterface
{
    private TagAwareCacheInterface $cache;

    private LoggerInterface $logger;

    private LanguageRepository $languageRepository;

    /**
     * @param LanguageRepository $languageRepository
     * @param TagAwareCacheInterface $cache
     * @param LoggerInterface $logger
     */
    public function __construct(
        LanguageRepository $languageRepository,
        TagAwareCacheInterface $cache,
        LoggerInterface $logger
    ){
        $this->cache = $cache;
        $this->logger = $logger;
        $this->languageRepository = $languageRepository;
    }

    /**
     * @return array
     */
    public function getCachedLanguagesList(): array
    {
        try {
            return $this->cache->get(CacheKeyInterface::KEY_LANGUAGES, function (ItemInterface $item) {
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);

                return $this->getLanguagesList();
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getLanguagesList();
        }
    }

    /**
     * @return array
     */
    public function getLanguagesList(): array
    {
        $results = $this->languageRepository->findBy(['is_active' => true], ['id' => 'ASC']);

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[$result->getCode()] = $result;
        }

        return $formattedResults;
    }
}
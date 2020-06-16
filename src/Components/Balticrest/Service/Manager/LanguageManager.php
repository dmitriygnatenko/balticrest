<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

use App\Entity\Language;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class LanguageManager implements LanguageManagerInterface
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
    public function getCachedActiveLanguages(): array
    {
        try {
            return $this->cache->get(self::CACHE_KEY, function (ItemInterface $item) {
                $item->tag(self::CACHE_TAG);
                $item->expiresAfter(self::CACHE_EXPIRE_TIME);
                return $this->getActiveLanguages();
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);
            return $this->getActiveLanguages();
        }
    }

    /**
     * @return array
     */
    public function getActiveLanguages(): array
    {
        $results = $this->em->getRepository(Language::class)
            ->findBy(['is_active' => true], ['id' => 'ASC']);

        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[$result->getCode()] = $result;
        }

        return $formattedResults;
    }
}
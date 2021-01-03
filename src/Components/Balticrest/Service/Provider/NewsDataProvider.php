<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Admin\Service\Pager\Paginator;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\DTO\NewsListDTO;
use App\Components\Balticrest\Service\Mapper\NewsListDTOMapper;
use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class NewsDataProvider implements NewsDataProviderInterface
{
    /** @var RequestStack */
    private $requestStack;

    /** @var EntityManagerInterface */
    private $em;

    /** @var LoggerInterface */
    private $logger;

    /** @var TagAwareCacheInterface */
    private $cache;

    /** @var CacheManager */
    private $cacheManager;

    /** @var NewsListDTOMapper */
    private $newsListDTOMapper;

    /**
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $em
     * @param LoggerInterface $logger
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param NewsListDTOMapper $newsListDTOMapper
     */
    public function __construct(
        RequestStack $requestStack,
        EntityManagerInterface $em,
        LoggerInterface $logger,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        NewsListDTOMapper $newsListDTOMapper
    )
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->newsListDTOMapper = $newsListDTOMapper;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param int $page
     * @param string|null $tag
     *
     * @return NewsListDTO
     *
     * @throws Exception
     */
    public function getCachedData(int $page = 1, ?string $tag = null): NewsListDTO
    {
        $cacheKey = $this->cacheManager->getNewsCacheKey(
            $page, (string) $tag, $this->requestStack->getMasterRequest()->getLocale()
        );

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($page, $tag) {
                $item->tag([CacheTagInterface::TAG_NEWS]);
                $item->expiresAfter(CacheExpireInterface::EXPIRE_12_HOURS);

                return $this->getData($page, $tag);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getData($page, $tag);
        }
    }

    /**
     * @param int $page
     * @param string|null $tag
     *
     * @return NewsListDTO
     *
     * @throws Exception
     */
    public function getData(int $page = 1, ?string $tag = null): NewsListDTO
    {
        /** @var Paginator $results */
        $results = $this->em->getRepository(News::class)->getActivePaginatedNews($page, self::NEWS_PER_PAGE, $tag);

        return $this->newsListDTOMapper->fill($results);
    }
}
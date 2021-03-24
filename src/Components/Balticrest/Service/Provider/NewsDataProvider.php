<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\DTO\NewsListDTO;
use App\Components\Balticrest\Service\Mapper\NewsListDTOMapper;
use App\Repository\NewsRepository;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class NewsDataProvider implements NewsDataProviderInterface
{
    private RequestStack $requestStack;

    private LoggerInterface $logger;

    private TagAwareCacheInterface $cache;

    private CacheManagerInterface $cacheManager;

    private NewsListDTOMapper $newsListDTOMapper;

    private NewsRepository $newsRepository;

    /**
     * @param RequestStack $requestStack
     * @param NewsRepository $newsRepository
     * @param LoggerInterface $logger
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param NewsListDTOMapper $newsListDTOMapper
     */
    public function __construct(
        RequestStack $requestStack,
        NewsRepository $newsRepository,
        LoggerInterface $logger,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        NewsListDTOMapper $newsListDTOMapper
    ) {
        $this->requestStack = $requestStack;
        $this->newsListDTOMapper = $newsListDTOMapper;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->newsRepository = $newsRepository;
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
        $results = $this->newsRepository->getActivePaginatedNews($page, self::NEWS_PER_PAGE, $tag);

        return $this->newsListDTOMapper->fill($results);
    }
}
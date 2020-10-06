<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\Cache\CacheExpireInterface;
use App\Components\Balticrest\Service\Cache\CacheManager;
use App\Components\Balticrest\Service\Cache\CacheManagerInterface;
use App\Components\Balticrest\Service\Cache\CacheTagInterface;
use App\Components\Balticrest\Service\DTO\ArticleDTO;
use App\Components\Balticrest\Service\Mapper\ArticleDTOMapper;
use App\Entity\Article;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ArticleDataProvider implements ArticleDataProviderInterface
{
    /** @var EntityManager */
    private $em;

    /** @var RequestStack */
    private $requestStack;

    /** @var LoggerInterface */
    private $logger;

    /** @var TagAwareCacheInterface */
    private $cache;

    /** @var CacheManager */
    private $cacheManager;

    /** @var ArticleDTOMapper */
    private $articleDTOMapper;

    /**
     * @param EntityManagerInterface $em
     * @param RequestStack $requestStack
     * @param LoggerInterface $logger
     * @param TagAwareCacheInterface $cache
     * @param CacheManagerInterface $cacheManager
     * @param ArticleDTOMapper $articleDTOMapper
     */
    public function __construct(
        EntityManagerInterface $em,
        RequestStack $requestStack,
        LoggerInterface $logger,
        TagAwareCacheInterface $cache,
        CacheManagerInterface $cacheManager,
        ArticleDTOMapper $articleDTOMapper
    )
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
        $this->cache = $cache;
        $this->cacheManager = $cacheManager;
        $this->articleDTOMapper = $articleDTOMapper;
    }

    /**
     * @param string $url
     *
     * @return ArticleDTO|null
     */
    public function getCachedArticleData(string $url): ?ArticleDTO
    {
        $cacheKey = $this->cacheManager->getArticleCacheKey(
            $url, $this->requestStack->getMasterRequest()->getLocale()
        );

        try {
            return $this->cache->get($cacheKey, function (ItemInterface $item) use ($url) {
                $item->tag([CacheTagInterface::TAG_ARTICLES]);
                $item->expiresAfter(CacheExpireInterface::EXPIRE_WEEK);

                return $this->getArticleData($url);
            });
        } catch (InvalidArgumentException $exception) {
            $this->logger->error($exception);

            return $this->getArticleData($url);
        }
    }

    /**
     * @param string $url
     *
     * @return ArticleDTO|null
     */
    public function getArticleData(string $url): ?ArticleDTO
    {
        $article = $this->getArticle($url);

        if ($article === null) {
            return null;
        }

        return $this->articleDTOMapper->fill($article);
    }

    /**
     * @param string $url
     *
     * @return Article|null
     */
    public function getArticle(string $url): ?Article
    {
        return $this->em->getRepository(Article::class)->findOneBy(['url' => $url, 'is_active' => true]);
    }
}
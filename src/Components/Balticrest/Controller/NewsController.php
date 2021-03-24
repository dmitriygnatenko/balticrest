<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\NewsDataProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    /** @var int */
    private const DEFAULT_PAGE = 1;

    private NewsDataProviderInterface $newsDataProvider;

    /**
     * @param NewsDataProviderInterface $newsDataProvider
     */
    public function __construct(NewsDataProviderInterface $newsDataProvider)
    {
        $this->newsDataProvider = $newsDataProvider;
    }

    /**
     * @Route("/news", methods={"GET"}, name="news")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->render('balticrest/news/index.html.twig', [
            'news' => $this->newsDataProvider->getCachedData(self::DEFAULT_PAGE),
        ]);
    }

    /**
     * @Route(
     *     "/news/{tag}",
     *     requirements={
     *         "tag": "events|transport|offers"
     *     },
     *     methods={"GET"},
     *     name="news_tag"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function tag(Request $request): Response
    {
        $tag = $request->attributes->get('tag');

        return $this->render('balticrest/news/index.html.twig', [
            'news' => $this->newsDataProvider->getCachedData(self::DEFAULT_PAGE, $tag),
            'tag' => $tag
        ]);
    }

    /**
     * @Route(
     *     "/api/news/{page}",
     *     requirements={"page"="[1-9]\d*"},
     *     methods={"GET"},
     *     name="api_news"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function apiNews(Request $request): Response
    {
        $page = $request->attributes->getInt('page');

        return $this->render('balticrest/include/news.html.twig', [
            'news' => $this->newsDataProvider->getCachedData($page),
        ]);
    }

    /**
     * @Route(
     *     "/api/news/{tag}/{page}",
     *     requirements={
     *         "tag": "events|transport|offers",
     *         "page"="[1-9]\d*"
     *     },
     *     methods={"GET"},
     *     name="api_news_tag"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function apiNewsTag(Request $request): Response
    {
        $tag = $request->attributes->get('tag');
        $page = $request->attributes->getInt('page');

        return $this->render('balticrest/include/news.html.twig', [
            'news' => $this->newsDataProvider->getCachedData($page, $tag),
            'tag' => $tag
        ]);
    }
}
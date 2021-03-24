<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\ArticleDataProviderInterface;
use App\Components\Balticrest\Service\Provider\MapDataProviderInterface;
use App\Components\Balticrest\Service\Provider\NewsDataProviderInterface;
use App\Components\Balticrest\Service\Twig\TwigGlobalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    private ArticleDataProviderInterface $articleDataProvider;

    /**
     * @param ArticleDataProviderInterface $articleDataProvider
     */
    public function __construct(ArticleDataProviderInterface $articleDataProvider)
    {
        $this->articleDataProvider = $articleDataProvider;
    }

    /**
     * @Route("/", methods={"GET"}, name="main")
     *
     * @param MapDataProviderInterface $mapJsonDataProvider
     * @param TwigGlobalService $globalService
     * @param NewsDataProviderInterface $newsDataProvider
     *
     * @return Response
     */
    public function main(
        MapDataProviderInterface $mapJsonDataProvider,
        TwigGlobalService $globalService,
        NewsDataProviderInterface $newsDataProvider
    ): Response
    {
        return $this->render('balticrest/page/index.html.twig', [
            'map_data' => $mapJsonDataProvider->getCachedData($globalService->getDefaultCity()),
            'news' => array_slice($newsDataProvider->getCachedData()->getNews(), 0, 3)
        ]);
    }

    /**
     * @Route("/privacy_policy", methods={"GET"}, name="privacy_policy")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function privacy(Request $request): Response
    {
        return $this->showArticle($request->attributes->get('_route'));
    }

    /**
     * @Route("/contacts", methods={"GET"}, name="contacts")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function contacts(Request $request): Response
    {
        return $this->showArticle($request->attributes->get('_route'));
    }

    /**
     * @param string $url
     *
     * @return Response
     */
    private function showArticle(string $url): Response
    {
        $article = $this->articleDataProvider->getCachedArticleData($url);

        if ($article === null) {
            throw $this->createNotFoundException();
        }

        return $this->render('balticrest/page/article.html.twig', [
            'article' => $article
        ]);
    }
}
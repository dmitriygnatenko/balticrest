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
    /** @var NewsDataProviderInterface */
    private $newsDataProvider;

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
        $page = $request->query->getInt('page', 1);

        $data = $this->newsDataProvider->getCachedData($page);
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
        $page = $request->query->getInt('page', 1);
        $tag = $request->get('tag');

        $data = $this->newsDataProvider->getCachedData($page, $tag);
    }
}
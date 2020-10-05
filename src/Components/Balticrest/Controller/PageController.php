<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\MapJsonDataProviderInterface;
use App\Components\Balticrest\Service\Twig\TwigGlobalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="main")
     *
     * @param MapJsonDataProviderInterface $mapJsonDataProvider
     * @param TwigGlobalService $globalService
     *
     * @return Response
     */
    public function main(MapJsonDataProviderInterface $mapJsonDataProvider, TwigGlobalService $globalService): Response
    {
        return $this->render('balticrest/page/index.html.twig', [
            'map_data' => $mapJsonDataProvider->generateCityMapJsonData($globalService->getDefaultCity())
        ]);
    }
}
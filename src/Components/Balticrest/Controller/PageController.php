<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\MapJsonDataProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /** @var string */
    private const DEFAULT_CITY = 'svetlogorsk';

    /**
     * @Route("/", methods={"GET"}, name="main")
     *
     * @param MapJsonDataProviderInterface $mapJsonDataProvider
     *
     * @return Response
     */
    public function main(MapJsonDataProviderInterface $mapJsonDataProvider): Response
    {
        return $this->render('balticrest/page/index.html.twig', [
            'default_city' => self::DEFAULT_CITY,
            'map_data' => $mapJsonDataProvider->generateCityMapJsonData(self::DEFAULT_CITY)
        ]);
    }
}
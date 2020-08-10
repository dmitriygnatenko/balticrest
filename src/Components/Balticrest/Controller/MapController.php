<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\MapJsonDataProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /** @var MapJsonDataProviderInterface  */
    private $mapJsonDataProvider;

    /**
     * @param MapJsonDataProviderInterface $mapJsonDataProvider
     */
    public function __construct(MapJsonDataProviderInterface $mapJsonDataProvider)
    {
        $this->mapJsonDataProvider = $mapJsonDataProvider;
    }

    /**
     * @Route(
     *     "/{city}/{category}",
     *     requirements={
     *         "city": "svetlogorsk|zelenogradsk|otradnoe",
     *         "category": "hotels|medicine|cafes|shops|banks|museums|sport|transport"
     *     },
     *     defaults={
     *         "category": ""
     *     },
     *     methods={"GET"},
     *     name="map"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function map(Request $request): Response
    {
        return $this->render('balticrest/map/map.html.twig', [
            'map_data' => $this->mapJsonDataProvider->generateCityMapJsonData($request)
        ]);
    }
}

<?php

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\MapManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /** @var MapManagerInterface  */
    private $mapManager;

    /**
     * @param MapManagerInterface $mapManager
     */
    public function __construct(MapManagerInterface $mapManager)
    {
        $this->mapManager = $mapManager;
    }

    /**
     * @Route(
     *     "/{city}/{category}",
     *     requirements={
     *         "city": "svetlogorsk|zelenogradsk",
     *         "category": "hotels|medicine|cafes|shops|banks|museums|sport|transport|webcam"
     *     },
     *     defaults={
     *         "category": ""
     *     },
     *     methods={"GET"},
     *     name="map"
     * )
     * @param Request $request
     *
     * @return Response
     */
    public function map(Request $request): Response
    {
        return $this->render('balticrest/map/map.html.twig', [
            'map_data' => $this->mapManager->generateCityMapData($request)
        ]);
    }
}

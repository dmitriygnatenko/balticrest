<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Manager\MapDataManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /** @var MapDataManagerInterface  */
    private $mapDataManager;

    /**
     * @param MapDataManagerInterface $mapDataManager
     */
    public function __construct(MapDataManagerInterface $mapDataManager)
    {
        $this->mapDataManager = $mapDataManager;
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
     *
     * @param Request $request
     *
     * @return Response
     */
    public function map(Request $request): Response
    {
        return $this->render('balticrest/map/map.html.twig', [
            'map_data' => $this->mapDataManager->generateCityMapJsonData($request)
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\MapDataProviderInterface;
use App\Components\Balticrest\Service\Provider\PointTypeProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /** @var MapDataProviderInterface  */
    private $mapJsonDataProvider;

    /** @var PointTypeProviderInterface */
    public $pointTypeProvider;

    /**
     * @param MapDataProviderInterface $mapJsonDataProvider
     * @param PointTypeProviderInterface $pointTypeProvider
     */
    public function __construct(
        MapDataProviderInterface $mapJsonDataProvider,
        PointTypeProviderInterface $pointTypeProvider
    ) {
        $this->mapJsonDataProvider = $mapJsonDataProvider;
        $this->pointTypeProvider = $pointTypeProvider;
    }

    /**
     * @Route(
     *     "/{city}/{category}",
     *     requirements={
     *         "city": "svetlogorsk|zelenogradsk",
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
        $city = $request->get('city', '');
        $category = $request->get('category', '');

        return $this->render('balticrest/map/map.html.twig', [
            'point_types' => $this->pointTypeProvider->getCachedMapPointTypesList($city),
            'map_data' => $this->mapJsonDataProvider->getCachedData($city, $category)
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\MapJsonDataProviderInterface;
use App\Components\Balticrest\Service\Provider\PointTypeProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    /** @var MapJsonDataProviderInterface  */
    private $mapJsonDataProvider;

    /** @var PointTypeProviderInterface */
    public $pointTypeProvider;

    /**
     * @param MapJsonDataProviderInterface $mapJsonDataProvider
     * @param PointTypeProviderInterface $pointTypeProvider
     */
    public function __construct(
        MapJsonDataProviderInterface $mapJsonDataProvider,
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
            'point_types' => [
                'hotels' => 1,
                'medicine' => 1,
                'cafes' => 1,
                'shops' => 1,
                'banks' => 1,
                'museums' => 1,
                'sport' => 1,
                'transport' => 1,
            ],
            'map_data' => $this->mapJsonDataProvider->getCachedCityMapJsonData($city, $category)
        ]);
    }
}

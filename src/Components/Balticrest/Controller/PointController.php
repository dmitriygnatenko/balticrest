<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\Provider\PointTypeProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PointController extends AbstractController
{
    /**
     * @Route(
     *     "/{city}/{category}/list",
     *     requirements={
     *         "city": "svetlogorsk|zelenogradsk",
     *         "category": "hotels|medicine|cafes|shops|banks|museums|sport|transport"
     *     },
     *     methods={"GET"},
     *     name="point_list"
     * )
     *
     * @param Request $request
     * @param PointTypeProviderInterface $pointTypeProvider
     *
     * @return Response
     */
    public function pointList(Request $request, PointTypeProviderInterface $pointTypeProvider): Response
    {
        $city = $request->get('city', '');
        $category = $request->get('category', '');

        return $this->render('balticrest/point/list.html.twig', [
            'point_types' => $pointTypeProvider->getCachedListPointTypesList($city),
            //'map_data' => $this->mapJsonDataProvider->generateCityMapJsonData($city, $category)
        ]);
    }

    /**
     * @Route(
     *     "/{city}/{category}/point/{url}",
     *     requirements={
     *         "city": "svetlogorsk|zelenogradsk",
     *         "category": "hotels|medicine|cafes|shops|banks|museums|sport|transport"
     *     },
     *     methods={"GET"},
     *     name="point"
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function point(Request $request): Response
    {
        return $this->render('balticrest/point/point.html.twig');
    }
}
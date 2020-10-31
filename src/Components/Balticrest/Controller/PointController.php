<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use App\Components\Balticrest\Service\DTO\PointDTO;
use App\Components\Balticrest\Service\Provider\PointDataProviderInterface;
use App\Components\Balticrest\Service\Provider\ListDataProviderInterface;
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
     * @param ListDataProviderInterface $listDataProvider
     *
     * @return Response
     */
    public function pointList(
        Request $request,
        PointTypeProviderInterface $pointTypeProvider,
        ListDataProviderInterface $listDataProvider
    ): Response {
        $city = $request->get('city', '');
        $category = $request->get('category', '');

        return $this->render('balticrest/point/list.html.twig', [
            'point_types' => $pointTypeProvider->getCachedListPointTypesList($city),
            'list_data' => $listDataProvider->getCachedData($city, $category)
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
     * @param PointDataProviderInterface $pointDataProvider
     * @param ListDataProviderInterface $listDataProvider
     *
     * @return Response
     */
    public function point(
        Request $request,
        PointDataProviderInterface $pointDataProvider,
        ListDataProviderInterface $listDataProvider
    ): Response {
        $url = $request->get('url', '');

        /** @var PointDTO|null $point */
        $point = $pointDataProvider->getCachedPointData($url);

        if ($point === null) {
            throw $this->createNotFoundException();
        }

        return $this->render('balticrest/point/point.html.twig', [
            'point' => $point,
            'similar_points' => $listDataProvider->getCachedSimilarPointsData($point)
        ]);
    }
}
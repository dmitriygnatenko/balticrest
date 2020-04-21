<?php

namespace App\Components\Balticrest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
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
        $city = $request->attributes->get('city');
        $category = $request->attributes->get('category');

        return $this->render('balticrest/map/map.html.twig', [
        ]);
    }

}

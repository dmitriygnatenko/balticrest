<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

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
     *
     * @return Response
     */
    public function pointList(Request $request): Response
    {
        // TODO
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
        // TODO
    }
}
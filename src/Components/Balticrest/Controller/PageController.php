<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="main")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function main(Request $request): Response
    {
        return new Response();
    }
}
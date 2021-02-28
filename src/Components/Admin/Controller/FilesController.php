<?php

declare(strict_types=1);

namespace App\Components\Admin\Controller;

use elFinder;
use elFinderConnector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Exception;

class FilesController extends AbstractController
{
    /**
     * @Route("/files", name="admin.files", methods={"GET"})
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/files/index.html.twig');
    }

    /**
     * @Route("/files/connector", name="admin.files.connector", methods={"GET", "POST"})
     *
     * @see https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
     *
     * @param Request $request
     *
     * @throws Exception
     */
    public function connector(Request $request)
    {
        $opts = [
            'debug' => true,
            'roots' => [
                [
                    'driver' => 'LocalFileSystem',
                    'path' => 'files/',
                    'URL' => '/files/',
                ],
            ]
        ];

        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
    }
}
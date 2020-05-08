<?php

namespace App\Components\Balticrest\Service\Manager;

use Symfony\Component\HttpFoundation\Request;

interface MapDataManagerInterface
{
    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapData(Request $request): string;
}
<?php

namespace App\Components\Balticrest\Service;

use Symfony\Component\HttpFoundation\Request;

interface MapManagerInterface
{
    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapData(Request $request): string;
}
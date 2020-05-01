<?php

namespace App\Components\Balticrest\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class MapManager implements MapManagerInterface
{


    public function __construct()
    {


    }


    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapData(Request $request): string
    {











        $data = [];

        $data['center']['lat'] = 54.943347;
        $data['center']['lon'] = 20.157184;
        $data['zoom'] = 15;







        $serializer = new Serializer([], [new JsonEncoder()]);
        return $serializer->serialize($data, 'json');
    }
}
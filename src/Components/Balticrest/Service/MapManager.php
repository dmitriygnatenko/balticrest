<?php

namespace App\Components\Balticrest\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class MapManager implements MapManagerInterface
{
    /** @var ContainerBagInterface */
    private $containerBag;

    /**
     * @param ContainerBagInterface $containerBag
     */
    public function __construct(ContainerBagInterface $containerBag)
    {
        $this->containerBag = $containerBag;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapData(Request $request): string
    {
        $city = $request->get('city', '');
        $category = $request->get('category', '');

        $cities = $this->containerBag->get('cities');

        $data = [];

        // Центр карты и масштаб
        if (isset($cities[$city])) {
            $data['center']['lat'] = $cities[$city]['center']['lat'];
            $data['center']['lon'] = $cities[$city]['center']['lon'];
            $data['zoom'] = $cities[$city]['zoom'];
        }

        // TODO category


        $serializer = new Serializer([], [new JsonEncoder()]);
        return $serializer->serialize($data, 'json');
    }
}
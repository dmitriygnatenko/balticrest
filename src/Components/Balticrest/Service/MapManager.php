<?php

namespace App\Components\Balticrest\Service;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;

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

        // TODO получать данные из БД и использовать маппер

        $listDTO = new MapPointsListDTO();

        $listDTO->setTransPointButton('Подробнее');

        // Центр карты и масштаб
        if (isset($cities[$city])) {
            $listDTO->setCenterLat($cities[$city]['center']['lat'])
                ->setCenterLon($cities[$city]['center']['lon'])
                ->setZoom($cities[$city]['zoom']);
        }

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.958713)
            ->setLon(20.472676)
            ->setHint('Рога и копыта')
            ->setTitle('ООО "Рога и копыта" fdgfdg df gdf')
            ->setDescription('Описание компании Рога и копыта в пару строк')
            ->setImage('/static/balticrest/images/cities/zelenogradsk.png')
            ->setIconImage('/static/balticrest/images/markers/marker-hotel.png')
            ->setIconImageX(29)
            ->setIconImageY(46)
        );

        return $listDTO->getJsonResult();
    }
}
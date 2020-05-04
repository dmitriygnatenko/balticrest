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

        $listDTO = new MapPointsListDTO();

        $listDTO->setTransPointButton('Подробнее');

        // Центр карты и масштаб
        if (isset($cities[$city])) {
            $listDTO->setCenterLat($cities[$city]['center']['lat'])
                ->setCenterLon($cities[$city]['center']['lon'])
                ->setZoom($cities[$city]['zoom']);
        }

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.956757)
            ->setLon(20.463495)
            ->setHint('Рога и копыта')
            ->setTitle('ООО "Рога и копыта" fdgfdg df gdf')
            ->setDescription('Описание компании Рога и копыта в пару строк')
            ->setImage('static/balticrest/images/cities/zelenogradsk.png')
            ->setPreset('islands#yellowHotelIcon')
        );

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.956647)
            ->setLon(20.474495)
            ->setTitle('Рога и копыта')
            ->setPreset('islands#pinkFoodIcon')
        );

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.955647)
            ->setLon(20.474495)
            ->setTitle('Рога и копыта')
            ->setPreset('islands#darkOrangeShoppingIcon')
        );

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.954647)
            ->setLon(20.474495)
            ->setTitle('Рога и копыта')
            ->setPreset('islands#darkBlueMoneyIcon')
        );

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.953647)
            ->setLon(20.47295)
            ->setTitle('Рога и копыта')
            ->setPreset('islands#greenMedicalIcon')
        );

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.952647)
            ->setLon(20.47195)
            ->setTitle('Рога и копыта')
            ->setPreset('islands#redMassTransitIcon')
        );

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.951647)
            ->setLon(20.47095)
            ->setTitle('Рога и копыта')
            ->setPreset('islands#darkGreenSportIcon')
        );

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.950647)
            ->setLon(20.46595)
            ->setTitle('Рога и копыта')
            ->setPreset('islands#oliveSportIcon')
        );

        return $listDTO->getJsonResult();
    }
}
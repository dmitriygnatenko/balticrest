<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

use App\Components\Balticrest\Service\DTO\MapPointDTO;
use App\Components\Balticrest\Service\DTO\MapPointsListDTO;
use App\Entity\City;
use Symfony\Component\HttpFoundation\Request;

class MapDataManager implements MapDataManagerInterface
{
    /** @var CityManager */
    private $cityManager;

    /**
     * @param CityManagerInterface $cityManager
     */
    public function __construct(CityManagerInterface $cityManager)
    {
        $this->cityManager = $cityManager;
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

        $cities = $this->cityManager->getActiveCached();

        // TODO получать данные из БД и использовать маппер

        $listDTO = new MapPointsListDTO();

        $listDTO->setTransPointButton('Подробнее');

        // Центр карты и масштаб
        if (isset($cities[$city])) {
            /** @var City $cityEntity */
            $cityEntity = $cities[$city];

            $listDTO->setCenterLat($cityEntity->getLat())
                ->setCenterLon($cityEntity->getLon())
                ->setZoom($cityEntity->getZoom());
        }

        $listDTO->addPoint((new MapPointDTO())
            ->setLat(54.958713)
            ->setLon(20.472676)
            ->setHint('Рога и копыта')
            ->setTitle('ООО "Рога и копыта"')
            ->setDescription('Описание компании Рога и копыта в пару строк')
            ->setImage('/static/balticrest/images/cities/zelenogradsk.png')
            ->setIconImage('/static/balticrest/images/markers/marker-hotel.png')
            ->setIconImageX(29)
            ->setIconImageY(46)
        );

        return $listDTO->getJsonResult();
    }
}
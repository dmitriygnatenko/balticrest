<?php

namespace App\Components\Balticrest\Service\DTO;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

class MapPointsListDTO
{

    /** @var float */
    private $centerLat;

    /** @var float */
    private $centerLon;

    /** @var int */
    private $zoom;

    /** @var string */
    private $transPointButton;

    /** @var array */
    private $points = [];

    /**
     * @param float $centerLat
     *
     * @return MapPointsListDTO
     */
    public function setCenterLat(float $centerLat): MapPointsListDTO
    {
        $this->centerLat = $centerLat;

        return $this;
    }

    /**
     * @param string $centerLon
     *
     * @return MapPointsListDTO
     */
    public function setCenterLon(string $centerLon): MapPointsListDTO
    {
        $this->centerLon = $centerLon;

        return $this;
    }

    /**
     * @param int $zoom
     *
     * @return MapPointsListDTO
     */
    public function setZoom(int $zoom): MapPointsListDTO
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * @param string $transPointButton
     *
     * @return MapPointsListDTO
     */
    public function setTransPointButton(string $transPointButton): MapPointsListDTO
    {
        $this->transPointButton = $transPointButton;

        return $this;
    }

    /**
     * @param MapPointDTO $mapPointDTO
     *
     * @return MapPointsListDTO
     */
    public function addPoint(MapPointDTO $mapPointDTO): MapPointsListDTO
    {
        $this->points[] = $mapPointDTO->getArrayResult();

        return $this;
    }

    /**
     * @return string
     */
    public function getJsonResult(): string
    {
        $data = [
            'center' => [
                'lat' => (float) $this->centerLat,
                'lon' => (float) $this->centerLon,
            ],
            'trans' => [
                'point_button' => (string) $this->transPointButton,
            ],
            'zoom' => (int) $this->zoom,
            'points' => $this->points
        ];

        $serializer = new Serializer([], [new JsonEncoder()]);

        return $serializer->serialize($data, 'json');
    }
}
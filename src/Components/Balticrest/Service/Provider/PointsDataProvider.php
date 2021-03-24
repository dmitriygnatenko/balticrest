<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Repository\PointRepository;

class PointsDataProvider implements PointsDataProviderInterface
{
    private PointRepository $pointRepository;

    /**
     * @param PointRepository $pointRepository
     */
    public function __construct(PointRepository $pointRepository)
    {
        $this->pointRepository = $pointRepository;
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsByCityAndCategory(string $city, string $category): array
    {
        return $this->pointRepository->getPointsByCityAndCategory($city, $category);
    }

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsWithUrlByCityAndCategory(string $city, string $category): array
    {
        return $this->pointRepository->getPointsWithUrlByCityAndCategory($city, $category);
    }
}
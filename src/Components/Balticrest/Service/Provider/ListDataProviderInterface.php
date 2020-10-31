<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\DTO\PointDTO;

interface ListDataProviderInterface
{
    /** @var int */
    public const SIMILAR_POINTS_COUNT = 5;

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getCachedData(string $city, string $category): array;

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getData(string $city, string $category): array;

    /**
     * @param PointDTO $point
     *
     * @return array
     */
    public function getCachedSimilarPointsData(PointDTO $point): array;

    /**
     * @param PointDTO $point
     *
     * @return array
     */
    public function getSimilarPointsData(PointDTO $point): array;
}
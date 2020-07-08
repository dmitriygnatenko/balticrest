<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface PointDataProviderInterface
{
    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsByCityAndCategory(string $city, string $category): array;
}
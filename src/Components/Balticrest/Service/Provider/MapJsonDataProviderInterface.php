<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface MapJsonDataProviderInterface
{
    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function generateCityMapJsonData(string $city, string $category = ''): string;
}
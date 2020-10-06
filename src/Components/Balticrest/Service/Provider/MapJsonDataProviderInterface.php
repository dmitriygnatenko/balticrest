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
    public function getCachedCityMapJsonData(string $city, string $category = ''): string;

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getCityMapJsonData(string $city, string $category = ''): string;
}
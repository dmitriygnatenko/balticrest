<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface MapDataProviderInterface
{
    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getCachedData(string $city, string $category = ''): string;

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function getData(string $city, string $category = ''): string;
}
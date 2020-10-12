<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

interface ListDataProviderInterface
{
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
}
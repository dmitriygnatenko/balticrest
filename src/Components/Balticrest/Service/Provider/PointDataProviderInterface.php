<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Components\Balticrest\Service\DTO\PointDTO;

interface PointDataProviderInterface
{
    /**
     * @param string $url
     *
     * @return PointDTO|null
     */
    public function getCachedPointData(string $url): ?PointDTO;

    /**
     * @param string $url
     *
     * @return PointDTO|null
     */
    public function getPointData(string $url): ?PointDTO;
}
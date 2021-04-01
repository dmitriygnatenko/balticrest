<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Schedule;

use App\Components\Balticrest\Service\DTO\ScheduleListDTO;

interface ScheduleDataProviderInterface
{
    /**
     * @param string $url
     *
     * @return ScheduleListDTO[]|null
     */
    public function getCachedPointData(string $url): ?array;

    /**
     * @param string $url
     *
     * @return ScheduleListDTO[]|null
     */
    public function getPointData(string $url): ?array;
}
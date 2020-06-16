<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

interface PointManagerInterface
{
    /** @var string */
    const POINT_TYPE_CACHE_KEY = 'balticrest.point.point_types';

    /** @var int Неделя */
    const POINT_TYPE_CACHE_EXPIRE_TIME = 604800;

    /** @var string */
    const POINT_TYPE_CACHE_TAG = 'point_type';

    /**
     * @return array
     */
    public function getCachedPointTypes(): array;

    /**
     * @return array
     */
    public function getPointTypes(): array;
}
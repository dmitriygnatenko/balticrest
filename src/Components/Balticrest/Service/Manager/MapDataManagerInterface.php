<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

use Symfony\Component\HttpFoundation\Request;

interface MapDataManagerInterface
{
    /** @var string */
    const CACHE_KEY = 'balticrest.map.city';

    /** @var int Неделя */
    const CACHE_EXPIRE_TIME = 604800;

    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapJsonData(Request $request): string;
}
<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Manager;

use Symfony\Component\HttpFoundation\Request;

interface MapDataManagerInterface
{
    /** @var int */
    const ICON_IMAGE_WIDTH = 27;

    /** @var int */
    const ICON_IMAGE_HEIGHT = 48;

    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapJsonData(Request $request): string;
}
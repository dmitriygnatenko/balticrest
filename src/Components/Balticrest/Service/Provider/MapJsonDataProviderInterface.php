<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use Symfony\Component\HttpFoundation\Request;

interface MapJsonDataProviderInterface
{
    /** @var int */
    public const ICON_IMAGE_WIDTH = 27;

    /** @var int */
    public const ICON_IMAGE_HEIGHT = 48;

    /**
     * @param Request $request
     *
     * @return string
     */
    public function generateCityMapJsonDataFromRequest(Request $request): string;

    /**
     * @param string $city
     * @param string $category
     *
     * @return string
     */
    public function generateCityMapJsonData(string $city, string $category = ''): string;
}
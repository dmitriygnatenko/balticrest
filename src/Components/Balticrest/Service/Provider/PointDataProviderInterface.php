<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Provider;

use App\Entity\Point;

interface PointDataProviderInterface
{
    /** @var string */
    public const IMAGES_PATH = '/static/balticrest/images/';

    /** @var int */
    public const ICON_IMAGE_WIDTH = 27;

    /** @var int */
    public const ICON_IMAGE_HEIGHT = 48;

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsByCityAndCategory(string $city, string $category): array;

    /**
     * @param string $city
     * @param string $category
     *
     * @return array
     */
    public function getPointsWithUrlByCityAndCategory(string $city, string $category): array;

    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointIconImage(Point $point): string;

    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointImage(Point $point): string;
}
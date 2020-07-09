<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use App\Entity\Point;

interface MapPointHelperInterface
{
    /** @var string */
    public const IMAGES_PATH = '/static/balticrest/images/';

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
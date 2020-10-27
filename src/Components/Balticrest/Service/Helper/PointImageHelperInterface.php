<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use App\Entity\Point;

interface PointImageHelperInterface
{
    /** @var string */
    public const IMAGES_PATH = '/static/balticrest/images/';

    /** @var int */
    public const ICON_IMAGE_WIDTH = 27;

    /** @var int */
    public const ICON_IMAGE_HEIGHT = 48;

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

    /**
     * @return int
     */
    public function getIconImageWidth(): int;

    /**
     * @return int
     */
    public function getIconImageHeight(): int;
}
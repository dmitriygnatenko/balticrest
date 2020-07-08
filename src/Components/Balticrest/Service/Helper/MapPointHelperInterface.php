<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use App\Entity\Point;

interface MapPointHelperInterface
{
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
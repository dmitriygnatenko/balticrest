<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use App\Entity\Point;
use App\Entity\PointLangData;

interface PointLangDataHelperInterface
{
    /**
     * @param Point $point
     * @param string $locale
     *
     * @return PointLangData|null
     */
    public function getLangPointData(Point $point, string $locale): ?PointLangData;
}
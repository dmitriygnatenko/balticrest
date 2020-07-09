<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Helper;

use App\Entity\Interfaces\PointDataFieldsInterface as PointFields;
use App\Entity\Point;

class MapPointHelper implements MapPointHelperInterface
{
    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointIconImage(Point $point): string
    {
        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
            return self::IMAGES_PATH . 'markers/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return self::IMAGES_PATH . 'markers/'  . $point->getType()->getCode() . '.png';
    }

    /**
     * @param Point $point
     *
     * @return string
     */
    public function getPointImage(Point $point): string
    {
        $pointLogo = $point->getLogo();

        if ($pointLogo) {
            return $pointLogo;
        }

        $pointData = $point->getData();

        if ($pointData[PointFields::FIELD_DETAILED_TYPE] ?? '') {
            return self::IMAGES_PATH . 'logo/default/'  . $pointData[PointFields::FIELD_DETAILED_TYPE] . '.png';
        }

        return self::IMAGES_PATH . 'logo/default/'  . $point->getType()->getCode() . '.png';
    }
}
<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

use App\Entity\Point;
use App\Entity\PointLangData;

class PointDTO
{
    /** @var int */
    public $id;

    /** @var string */
    public $type;

    /** @var string */
    public $city;

    /** @var string */
    public $title;

    /** @var boolean */
    public $is_active;

    /**
     * @param Point $point
     *
     * @return $this
     */
    public function fillByPoint(Point $point): self
    {
        $this->id = $point->getId();
        $this->is_active = $point->getIsActive();
        $this->city = $point->getCity()->getCode();
        $this->type = $point->getType()->getCode();

        $pointRuData = $point->getPointLangData()->filter(static function($pointLangData) {
            /** @var PointLangData $pointLangData */
            return $pointLangData->getLanguage()->getCode() === 'ru';
        });

        if (!$pointRuData->isEmpty()) {
            $this->title = $pointRuData->first()->getTitle();
        }

        return $this;
    }
}
<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Mapper;

use App\Components\Admin\Service\DTO\PointDTO;
use App\Entity\Point;
use App\Entity\PointLangData;

class PointDTOMapper
{
    /**
     * @param Point $point
     * @param PointDTO $dto
     *
     * @return PointDTO
     */
    public function fill(Point $point, PointDTO $dto): PointDTO
    {
        $dto
            ->setId((int) $point->getId())
            ->setType((string) $point->getType()->getCode())
            ->setCity((string) $point->getCity()->getCode())
            ->setIsActive((bool) $point->getIsActive());

        $ruData = $point->getPointLangData()->filter(static function($langData) {
            /** @var PointLangData $langData */
            return $langData->getLanguage()->getCode() === 'ru';
        });

        if (!$ruData->isEmpty()) {
            $dto->setTitle((string) $ruData->first()->getTitle());
        }

        return $dto;
    }

    /**
     * @param Point[] $points
     *
     * @return PointDTO[]
     */
    public function fillAll(array $points): array
    {
        $dtoArray = [];

        foreach ($points as $point) {
            $dtoArray[] = $this->fill($point, new PointDTO());
        }

        return $dtoArray;
    }
}
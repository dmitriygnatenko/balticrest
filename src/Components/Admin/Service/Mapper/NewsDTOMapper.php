<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Mapper;

use App\Components\Admin\Service\DTO\NewsDTO;
use App\Entity\News;
use App\Entity\NewsLangData;

class NewsDTOMapper
{
    /**
     * @param News $news
     * @param NewsDTO $dto
     *
     * @return NewsDTO
     */
    public function fill(News $news, NewsDTO $dto): NewsDTO
    {
        $dto
            ->setId((int) $news->getId())
            ->setPublishDate($news->getPublishDate() !== null ? $news->getPublishDate()->format('d.m.Y') : '')
            ->setIsActive((bool) $news->getIsActive());

        $ruData = $news->getNewsLangData()->filter(static function($langData) {
            /** @var NewsLangData $langData */
            return $langData->getLanguage()->getCode() === 'ru';
        });

        if (!$ruData->isEmpty()) {
            $dto->setTitle($ruData->first()->getTitle());
        }

        return $dto;
    }

    /**
     * @param News[] $news
     *
     * @return NewsDTO[]
     */
    public function fillAll(array $news): array
    {
        $dtoArray = [];

        foreach ($news as $record) {
            $dtoArray[] = $this->fill($record, new NewsDTO());
        }

        return $dtoArray;
    }
}
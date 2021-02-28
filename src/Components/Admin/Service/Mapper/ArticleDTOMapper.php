<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Mapper;

use App\Components\Admin\Service\DTO\ArticleDTO;
use App\Entity\Article;
use App\Entity\ArticleLangData;

class ArticleDTOMapper
{
    /**
     * @param Article $article
     * @param ArticleDTO $dto
     *
     * @return ArticleDTO
     */
    public function fill(Article $article, ArticleDTO $dto): ArticleDTO
    {
        $dto
            ->setId((int) $article->getId())
            ->setIsActive((bool) $article->getIsActive());

        $ruData = $article->getArticleLangData()->filter(static function($langData) {
            /** @var ArticleLangData $langData */
            return $langData->getLanguage()->getCode() === 'ru';
        });

        if (!$ruData->isEmpty()) {
            $dto->setTitle($ruData->first()->getTitle());
        }

        return $dto;
    }

    /**
     * @param Article[] $articles
     *
     * @return ArticleDTO[]
     */
    public function fillAll(array $articles): array
    {
        $dtoArray = [];

        foreach ($articles as $article) {
            $dtoArray[] = $this->fill($article, new ArticleDTO());
        }

        return $dtoArray;
    }
}
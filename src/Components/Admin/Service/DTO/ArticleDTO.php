<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

use App\Entity\Article;
use App\Entity\ArticleLangData;

class ArticleDTO
{
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var boolean */
    public $is_active;

    /**
     * @param Article $article
     *
     * @return $this
     */
    public function fillByArticle(Article $article): self
    {
        $this->id = $article->getId();
        $this->is_active = $article->getIsActive();

        $pointRuData = $article->getArticleLangData()->filter(static function($articleLangData) {
            /** @var ArticleLangData $articleLangData */
            return $articleLangData->getLanguage()->getCode() === 'ru';
        });

        if (!$pointRuData->isEmpty()) {
            $this->title = $pointRuData->first()->getTitle();
        }

        return $this;
    }
}
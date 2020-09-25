<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

use App\Entity\News;
use App\Entity\NewsLangData;

class NewsDTO
{
    /** @var int */
    public $id;

    /** @var string */
    public $title;

    /** @var string */
    public $publish_date;

    /** @var boolean */
    public $is_active;

    /**
     * @param News $news
     *
     * @return $this
     */
    public function fillByNews(News $news): self
    {
        $this->id = $news->getId();
        $this->is_active = $news->getIsActive();
        $this->publish_date = $news->getPublishDate() !== null ? $news->getPublishDate()->format('d.m.Y') : '';

        $newsRuData = $news->getNewsLangData()->filter(static function($newsLangData) {
            /** @var NewsLangData $newsLangData */
            return $newsLangData->getLanguage()->getCode() === 'ru';
        });

        if (!$newsRuData->isEmpty()) {
            $this->title = $newsRuData->first()->getTitle();
        }

        return $this;
    }
}
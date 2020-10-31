<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class ArticleDTO
{
    /** @var string|null */
    private $title;

    /** @var string|null */
    private $text;

    /** @var string|null */
    private $meta_keywords;

    /** @var string|null */
    private $meta_description;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return ArticleDTO
     */
    public function setTitle(string $title): ArticleDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return ArticleDTO
     */
    public function setText(string $text): ArticleDTO
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaKeywords(): ?string
    {
        return $this->meta_keywords;
    }

    /**
     * @param string $metaKeywords
     *
     * @return ArticleDTO
     */
    public function setMetaKeywords(string $metaKeywords): ArticleDTO
    {
        $this->meta_keywords = $metaKeywords;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->meta_description;
    }

    /**
     * @param string $metaDescription
     *
     * @return ArticleDTO
     */
    public function setMetaDescription(string $metaDescription): ArticleDTO
    {
        $this->meta_description = $metaDescription;

        return $this;
    }
}
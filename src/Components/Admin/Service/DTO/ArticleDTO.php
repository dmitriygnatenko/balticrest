<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

class ArticleDTO
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var boolean */
    private $is_active;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return ArticleDTO
     */
    public function setId(int $id): ArticleDTO
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
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
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    /**
     * @param bool $isActive
     *
     * @return ArticleDTO
     */
    public function setIsActive(bool $isActive): ArticleDTO
    {
        $this->is_active = $isActive;

        return $this;
    }
}
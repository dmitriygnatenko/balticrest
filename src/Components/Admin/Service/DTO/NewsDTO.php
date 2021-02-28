<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

class NewsDTO
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $publish_date;

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
     * @return NewsDTO
     */
    public function setId(int $id): NewsDTO
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
     * @return NewsDTO
     */
    public function setTitle(string $title): NewsDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublishDate(): string
    {
        return $this->publish_date;
    }

    /**
     * @param string $publishDate
     *
     * @return NewsDTO
     */
    public function setPublishDate(string $publishDate): NewsDTO
    {
        $this->publish_date = $publishDate;

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
     * @return NewsDTO
     */
    public function setIsActive(bool $isActive): NewsDTO
    {
        $this->is_active = $isActive;

        return $this;
    }
}
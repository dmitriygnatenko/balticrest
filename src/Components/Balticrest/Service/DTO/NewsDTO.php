<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

use DateTimeInterface;

class NewsDTO
{
    /** @var int|null */
    private $id;

    /** @var string|null */
    private $image;

    /** @var array */
    private $tags = [];

    /** @var DateTimeInterface|null */
    private $publish_date;

    /** @var string|null */
    private $title;

    /** @var string|null */
    private $text;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return NewsDTO
     */
    public function setId(?int $id): NewsDTO
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     *
     * @return NewsDTO
     */
    public function setImage(?string $image): NewsDTO
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return NewsDTO
     */
    public function setTags(array $tags): NewsDTO
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPublishDate(): ?DateTimeInterface
    {
        return $this->publish_date;
    }

    /**
     * @param DateTimeInterface|null $publishDate
     *
     * @return NewsDTO
     */
    public function setPublishDate(?DateTimeInterface $publishDate): NewsDTO
    {
        $this->publish_date = $publishDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return NewsDTO
     */
    public function setTitle(?string $title): NewsDTO
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
     * @param string|null $text
     *
     * @return NewsDTO
     */
    public function setText(?string $text): NewsDTO
    {
        $this->text = $text;

        return $this;
    }
}
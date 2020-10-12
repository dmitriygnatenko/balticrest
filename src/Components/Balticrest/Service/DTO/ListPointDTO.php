<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class ListPointDTO
{
    /** @var string */
    private $title;

    /** @var string */
    private $link;

    /** @var string */
    private $description;

    /** @var string */
    private $image;

    /**
     * @param string $title
     *
     * @return ListPointDTO
     */
    public function setTitle(string $title): ListPointDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return ListPointDTO
     */
    public function setDescription(string $description): ListPointDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $image
     *
     * @return ListPointDTO
     */
    public function setImage(string $image): ListPointDTO
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param string $link
     *
     * @return ListPointDTO
     */
    public function setLink(string $link): ListPointDTO
    {
        $this->link = $link;

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
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
}
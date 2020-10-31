<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class ListPointDTO
{
    /** @var int|null */
    private $id;

    /** @var string|null */
    private $title;

    /** @var string|null */
    private $link;

    /** @var string|null */
    private $description;

    /** @var string|null */
    private $address;

    /** @var string|null */
    private $image;

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
     * @return ListPointDTO
     */
    public function setId(?int $id): ListPointDTO
    {
        $this->id = $id;

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
     * @return ListPointDTO
     */
    public function setTitle(?string $title): ListPointDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     *
     * @return ListPointDTO
     */
    public function setLink(?string $link): ListPointDTO
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return ListPointDTO
     */
    public function setDescription(?string $description): ListPointDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     *
     * @return ListPointDTO
     */
    public function setAddress(?string $address): ListPointDTO
    {
        $this->address = $address;

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
     * @return ListPointDTO
     */
    public function setImage(?string $image): ListPointDTO
    {
        $this->image = $image;

        return $this;
    }
}
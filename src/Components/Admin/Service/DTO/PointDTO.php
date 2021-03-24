<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

class PointDTO
{
    private ?int $id = null;

    private ?string $type = null;

    private ?string $city = null;

    private ?string $title = null;

    private bool $is_active = true;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return PointDTO
     */
    public function setId(int $id): PointDTO
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return PointDTO
     */
    public function setType(string $type): PointDTO
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return PointDTO
     */
    public function setCity(string $city): PointDTO
    {
        $this->city = $city;

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
     * @param string $title
     *
     * @return PointDTO
     */
    public function setTitle(string $title): PointDTO
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
     * @return PointDTO
     */
    public function setIsActive(bool $isActive): PointDTO
    {
        $this->is_active = $isActive;

        return $this;
    }
}
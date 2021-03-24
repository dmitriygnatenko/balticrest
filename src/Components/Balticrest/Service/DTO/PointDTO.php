<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class PointDTO
{
    private ?int $id = null;

    private ?string $title = null;

    private ?string $description = null;

    private ?string $address = null;

    private ?string $phones = null;

    private ?float $lat = null;

    private ?float $lon = null;

    private ?string $link = null;

    private ?string $image = null;

    private ?string $email = null;

    private ?string $website = null;

    private array $services = [];

    private ?string $category = null;

    private ?string $city = null;

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
     * @return PointDTO
     */
    public function setId(?int $id): PointDTO
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float|null $lat
     *
     * @return PointDTO
     */
    public function setLat(?float $lat): PointDTO
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLon(): ?float
    {
        return $this->lon;
    }

    /**
     * @param float|null $lon
     *
     * @return PointDTO
     */
    public function setLon(?float $lon): PointDTO
    {
        $this->lon = $lon;

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
     * @return PointDTO
     */
    public function setImage(?string $image): PointDTO
    {
        $this->image = $image;

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
     * @return PointDTO
     */
    public function setLink(?string $link): PointDTO
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
     * @param string|null $title
     *
     * @return PointDTO
     */
    public function setTitle(?string $title): PointDTO
    {
        $this->title = $title;

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
     * @return PointDTO
     */
    public function setDescription(?string $description): PointDTO
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
     * @return PointDTO
     */
    public function setAddress(?string $address): PointDTO
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     *
     * @return PointDTO
     */
    public function setEmail(?string $email): PointDTO
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->website;
    }

    /**
     * @param string|null $website
     *
     * @return PointDTO
     */
    public function setWebsite(?string $website): PointDTO
    {
        $this->website = $website;

        return $this;
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @param array $services
     *
     * @return PointDTO
     */
    public function setServices(array $services): PointDTO
    {
        $this->services = $services;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhones(): ?string
    {
        return $this->phones;
    }

    /**
     * @param string|null $phones
     *
     * @return PointDTO
     */
    public function setPhones(?string $phones): PointDTO
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     *
     * @return PointDTO
     */
    public function setCategory(?string $category): PointDTO
    {
        $this->category = $category;

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
     * @param string|null $city
     *
     * @return PointDTO
     */
    public function setCity(?string $city): PointDTO
    {
        $this->city = $city;

        return $this;
    }
}
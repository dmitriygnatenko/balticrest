<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\DTO;

class MapPointDTO
{
    /** @var float */
    private $lat;

    /** @var float */
    private $lon;

    /** @var string */
    private $hint;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var string */
    private $image;

    /** @var string */
    private $link;

    /** @var string */
    private $iconImage;

    /** @var int */
    private $iconImageX;

    /** @var int */
    private $iconImageY;

    /**
     * @param float $lat
     *
     * @return MapPointDTO
     */
    public function setLat(float $lat): MapPointDTO
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * @param float $lon
     *
     * @return MapPointDTO
     */
    public function setLon(float $lon): MapPointDTO
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return MapPointDTO
     */
    public function setTitle(string $title): MapPointDTO
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $hint
     *
     * @return MapPointDTO
     */
    public function setHint(string $hint): MapPointDTO
    {
        $this->hint = $hint;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return MapPointDTO
     */
    public function setDescription(string $description): MapPointDTO
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $image
     *
     * @return MapPointDTO
     */
    public function setImage(string $image): MapPointDTO
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @param string $link
     *
     * @return MapPointDTO
     */
    public function setLink(string $link): MapPointDTO
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @param string $iconImage
     *
     * @return MapPointDTO
     */
    public function setIconImage(string $iconImage): MapPointDTO
    {
        $this->iconImage = $iconImage;

        return $this;
    }

    /**
     * @param int $iconImageX
     *
     * @return MapPointDTO
     */
    public function setIconImageX(int $iconImageX): MapPointDTO
    {
        $this->iconImageX = $iconImageX;

        return $this;
    }

    /**
     * @param int $iconImageY
     *
     * @return MapPointDTO
     */
    public function setIconImageY(int $iconImageY): MapPointDTO
    {
        $this->iconImageY = $iconImageY;

        return $this;
    }

    /**
     * @return array
     */
    public function getArrayResult(): array
    {
        return [
            'lat' => (float) $this->lat,
            'lon' => (float) $this->lon,
            'hint' => (string) $this->hint,
            'title' => (string) $this->title,
            'description' => (string) $this->description,
            'image' => (string) $this->image,
            'link' => (string) $this->link,
            'icon' => [
                'image' => $this->iconImage,
                'size' => [
                    'x' => (int) $this->iconImageX,
                    'y' => (int) $this->iconImageY,
                ]
            ]
        ];
    }
}
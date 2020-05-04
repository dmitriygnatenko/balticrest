<?php

namespace App\Components\Balticrest\Service\DTO;

class MapPointDTO
{
    /** @var float */
    private $lat;

    /** @var float */
    private $lon;

    /** @var string */
    private $preset;

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
     * @param string $preset
     *
     * @return MapPointDTO
     */
    public function setPreset(string $preset): MapPointDTO
    {
        $this->preset = $preset;
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
            'preset' => (string)$this->preset
        ];
    }
}
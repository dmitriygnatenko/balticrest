<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointRepository")
 * @ORM\Table(
 *     name="point",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"url"})
 *     },
 *     indexes={
 *          @ORM\Index(columns={"is_active"})
 *     }
 * )
 */
class Point
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PointType", inversedBy="points")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="float")
     */
    private $lat;

    /**
     * @ORM\Column(type="float")
     */
    private $lon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private $is_active = true;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PointLangData", mappedBy="point", orphanRemoval=true)
     */
    private $pointLangData;

    public function __construct()
    {
        $this->pointLangData = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     *
     * @return $this
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return PointType|null
     */
    public function getType(): ?PointType
    {
        return $this->type;
    }

    /**
     * @param PointType|null $type
     *
     * @return $this
     */
    public function setType(?PointType $type): self
    {
        $this->type = $type;

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
     * @param float $lat
     *
     * @return $this
     */
    public function setLat(float $lat): self
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
     * @param float $lon
     *
     * @return $this
     */
    public function setLon(float $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     *
     * @return $this
     */
    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;

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
     * @param bool $is_active
     *
     * @return $this
     */
    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    /**
     * @return Collection|PointLangData[]
     */
    public function getPointLangData(): Collection
    {
        return $this->pointLangData;
    }

    /**
     * @param PointLangData $pointLangData
     *
     * @return $this
     */
    public function addPointLangData(PointLangData $pointLangData): self
    {
        if (!$this->pointLangData->contains($pointLangData)) {
            $this->pointLangData[] = $pointLangData;
            $pointLangData->setPoint($this);
        }

        return $this;
    }

    /**
     * @param PointLangData $pointLangData
     *
     * @return $this
     */
    public function removePointLangData(PointLangData $pointLangData): self
    {
        if ($this->pointLangData->contains($pointLangData)) {
            $this->pointLangData->removeElement($pointLangData);
            if ($pointLangData->getPoint() === $this) {
                $pointLangData->setPoint(null);
            }
        }

        return $this;
    }
}

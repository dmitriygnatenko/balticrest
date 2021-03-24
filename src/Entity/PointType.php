<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PointTypeRepository")
 * @ORM\Table(
 *     name="point_type",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"code"})
 *     }
 * )
 */
class PointType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $code = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Point", mappedBy="type", orphanRemoval=true)
     */
    private Collection $points;

    public function __construct()
    {
        $this->points = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Point[]
     */
    public function getPoints(): Collection
    {
        return $this->points;
    }

    /**
     * @param Point $point
     *
     * @return $this
     */
    public function addPoint(Point $point): self
    {
        if (!$this->points->contains($point)) {
            $this->points[] = $point;
            $point->setType($this);
        }

        return $this;
    }

    /**
     * @param Point $point
     *
     * @return $this
     */
    public function removePoint(Point $point): self
    {
        if ($this->points->contains($point)) {
            $this->points->removeElement($point);
            if ($point->getType() === $this) {
                $point->setType(null);
            }
        }

        return $this;
    }
}

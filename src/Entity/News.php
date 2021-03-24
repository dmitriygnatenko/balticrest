<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\NewsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NewsRepository", repositoryClass=NewsRepository::class)
 * @ORM\Table(
 *     name="news",
 *     indexes={
 *          @ORM\Index(columns={"is_active"})
 *     }
 * )
 */
class News
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $image = null;

    /**
     * @ORM\Column(type="date")
     */
    private ?DateTimeInterface $publish_date = null;

    /**
     * @ORM\Column(type="json")
     */
    private array $tags = [];

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private bool $is_active = true;

    /**
     * @ORM\OneToMany(targetEntity=NewsLangData::class, mappedBy="news", orphanRemoval=true)
     */
    private Collection $newsLangData;

    public function __construct()
    {
        $this->newsLangData = new ArrayCollection();
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
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return $this
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

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
     * @param DateTimeInterface $publish_date
     *
     * @return $this
     */
    public function setPublishDate(DateTimeInterface $publish_date): self
    {
        $this->publish_date = $publish_date;

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
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

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
     * @return Collection|NewsLangData[]
     */
    public function getNewsLangData(): Collection
    {
        return $this->newsLangData;
    }

    /**
     * @param NewsLangData $newsLangData
     *
     * @return $this
     */
    public function addNewsLangData(NewsLangData $newsLangData): self
    {
        if (!$this->newsLangData->contains($newsLangData)) {
            $this->newsLangData[] = $newsLangData;
            $newsLangData->setNews($this);
        }

        return $this;
    }

    /**
     * @param NewsLangData $newsLangData
     *
     * @return $this
     */
    public function removeNewsLangData(NewsLangData $newsLangData): self
    {
        if ($this->newsLangData->contains($newsLangData)) {
            $this->newsLangData->removeElement($newsLangData);
            if ($newsLangData->getNews() === $this) {
                $newsLangData->setNews(null);
            }
        }

        return $this;
    }
}

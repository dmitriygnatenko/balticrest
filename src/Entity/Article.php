<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ORM\Table(
 *     name="article",
 *     uniqueConstraints={
 *          @ORM\UniqueConstraint(columns={"url"})
 *     },
 *     indexes={
 *          @ORM\Index(columns={"is_active"})
 *     }
 * )
 * @ORM\HasLifecycleCallbacks()
 */
class Article
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
    private ?string $url = null;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     */
    private bool $is_active = true;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $last_update_time = null;

    /**
     * @ORM\OneToMany(targetEntity=ArticleLangData::class, mappedBy="article", orphanRemoval=true)
     */
    private Collection $articleLangData;

    public function __construct()
    {
        $this->articleLangData = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistUpdate(): void
    {
        $this->last_update_time = new DateTimeImmutable();
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
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

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
     * @return DateTimeImmutable|null
     */
    public function getLastUpdateTime(): ?DateTimeImmutable
    {
        return $this->last_update_time;
    }

    /**
     * @param DateTimeImmutable|null $lastUpdateTime
     *
     * @return Article
     */
    public function setLastUpdateTime(?DateTimeImmutable $lastUpdateTime): Article
    {
        $this->last_update_time = $lastUpdateTime;

        return $this;
    }

    /**
     * @return Collection|ArticleLangData[]
     */
    public function getArticleLangData(): Collection
    {
        return $this->articleLangData;
    }

    /**
     * @param ArticleLangData $articleLangData
     *
     * @return $this
     */
    public function addArticleLangData(ArticleLangData $articleLangData): self
    {
        if (!$this->articleLangData->contains($articleLangData)) {
            $this->articleLangData[] = $articleLangData;
            $articleLangData->setArticle($this);
        }

        return $this;
    }

    /**
     * @param ArticleLangData $articleLangData
     *
     * @return $this
     */
    public function removeArticleLangData(ArticleLangData $articleLangData): self
    {
        if ($this->articleLangData->contains($articleLangData)) {
            $this->articleLangData->removeElement($articleLangData);
            if ($articleLangData->getArticle() === $this) {
                $articleLangData->setArticle(null);
            }
        }

        return $this;
    }
}

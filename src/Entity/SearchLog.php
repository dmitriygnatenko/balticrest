<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SearchLogRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=SearchLogRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class SearchLog
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
    private ?string $query;

    /**
     * @ORM\Column(type="smallint")
     */
    private ?int $found_results_count = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $create_time = null;

    /**
     * @ORM\PrePersist
     */
    public function prePersist(): void
    {
        $this->create_time = new DateTimeImmutable();
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
    public function getQuery(): ?string
    {
        return $this->query;
    }

    /**
     * @param string $query
     *
     * @return $this
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getFoundResultsCount(): ?int
    {
        return $this->found_results_count;
    }

    /**
     * @param int $foundResultsCount
     *
     * @return $this
     */
    public function setFoundResultsCount(int $foundResultsCount): self
    {
        $this->found_results_count = $foundResultsCount;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreateTime(): ?\DateTimeImmutable
    {
        return $this->create_time;
    }

    /**
     * @param DateTimeImmutable $createTime
     *
     * @return $this
     */
    public function setCreateTime(\DateTimeImmutable $createTime): self
    {
        $this->create_time = $createTime;

        return $this;
    }
}

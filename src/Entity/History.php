<?php

namespace App\Entity;

use App\Entity\Interfaces\HistoryTypesInterface;
use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository", repositoryClass=HistoryRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class History implements HistoryTypesInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="smallint")
     */
    private $type;

    /**
     * @ORM\Column(type="json")
     */
    private $context = [];

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created;

    /**
     * @ORM\PrePersist
     */
    public function prePersistCreated(): void
    {
        $this->setCreated(new DateTimeImmutable());
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return History
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getType(): ?int
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return History
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @param array $context
     *
     * @return History
     */
    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getCreated(): ?DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @param DateTimeImmutable $created
     *
     * @return History
     */
    public function setCreated(DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }
}

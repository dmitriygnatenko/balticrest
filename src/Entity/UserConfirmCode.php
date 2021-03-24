<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Interfaces\UserConfirmCodeInterface;
use App\Repository\UserConfirmCodeRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserConfirmCodeRepository", repositoryClass=UserConfirmCodeRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class UserConfirmCode implements UserConfirmCodeInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user = null;

    /**
     * @ORM\Column(type="smallint")
     */
    private ?int $type = null;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private ?string $code = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeImmutable $created = null;

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
     * @param User $user
     *
     * @return $this
     */
    public function setUser(User $user): self
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
     * @return UserConfirmCode
     */
    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
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
     * @return DateTimeImmutable|null
     */
    public function getCreated(): ?DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @param DateTimeImmutable $created
     *
     * @return $this
     */
    public function setCreated(DateTimeImmutable $created): self
    {
        $this->created = $created;

        return $this;
    }
}

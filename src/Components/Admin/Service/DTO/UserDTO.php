<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

class UserDTO
{
    private ?int $id = null;

    private ?string $email = null;

    private bool $is_active = true;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return UserDTO
     */
    public function setId(int $id): UserDTO
    {
        $this->id = $id;

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
     * @param string $email
     *
     * @return UserDTO
     */
    public function setEmail(string $email): UserDTO
    {
        $this->email = $email;

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
     * @param bool $isActive
     *
     * @return UserDTO
     */
    public function setIsActive(bool $isActive): UserDTO
    {
        $this->is_active = $isActive;

        return $this;
    }
}
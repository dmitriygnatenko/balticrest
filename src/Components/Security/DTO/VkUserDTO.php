<?php

declare(strict_types=1);

namespace App\Components\Security\DTO;

class VkUserDTO
{
    /** @var string|null */
    private $id;

    /** @var string|null */
    private $email;

    /** @var string|null */
    private $firstName;

    /** @var string|null */
    private $lastName;

    /** @var string|null */
    private $photo;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return VkUserDTO
     */
    public function setId(?string $id): VkUserDTO
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
     * @param string|null $email
     *
     * @return VkUserDTO
     */
    public function setEmail(?string $email): VkUserDTO
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     *
     * @return VkUserDTO
     */
    public function setFirstName(?string $firstName): VkUserDTO
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     *
     * @return VkUserDTO
     */
    public function setLastName(?string $lastName): VkUserDTO
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     *
     * @return VkUserDTO
     */
    public function setPhoto(?string $photo): VkUserDTO
    {
        $this->photo = $photo;

        return $this;
    }
}
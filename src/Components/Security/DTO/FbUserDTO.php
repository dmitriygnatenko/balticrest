<?php

declare(strict_types=1);

namespace App\Components\Security\DTO;

class FbUserDTO
{
    /** @var string|null */
    private $id;

    /** @var string|null */
    private $email;

    /** @var string|null */
    private $name;

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
     * @return FbUserDTO
     */
    public function setId(?string $id): FbUserDTO
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
     * @return FbUserDTO
     */
    public function setEmail(?string $email): FbUserDTO
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return FbUserDTO
     */
    public function setName(?string $name): FbUserDTO
    {
        $this->name = $name;

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
     * @return FbUserDTO
     */
    public function setPhoto(?string $photo): FbUserDTO
    {
        $this->photo = $photo;

        return $this;
    }
}
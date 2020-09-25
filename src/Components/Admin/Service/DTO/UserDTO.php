<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\DTO;

use App\Entity\User;

class UserDTO
{
    /** @var int */
    public $id;

    /** @var string */
    public $email;

    /** @var boolean */
    public $is_active;

    /**
     * @param User $user
     *
     * @return $this
     */
    public function fillByUser(User $user): self
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->is_active = $user->getIsActive();

        return $this;
    }
}
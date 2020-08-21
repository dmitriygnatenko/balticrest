<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Event;

use Symfony\Contracts\EventDispatcher\Event;
use App\Entity\User;

class UserCreatedEvent extends Event
{
    /** @var User */
    private $user;

    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
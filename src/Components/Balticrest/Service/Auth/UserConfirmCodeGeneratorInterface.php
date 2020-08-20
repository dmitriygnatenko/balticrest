<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Auth;

use App\Entity\User;
use App\Entity\UserConfirmCode;

interface UserConfirmCodeGeneratorInterface
{
    /**
     * @param User $user
     * @param int $type
     *
     * @return UserConfirmCode
     */
    public function generate(User $user, int $type): UserConfirmCode;
}
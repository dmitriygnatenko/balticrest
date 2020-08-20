<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Auth;

use App\Entity\UserConfirmCode;

interface UserConfirmServiceInterface
{
    /**
     * @param string $code
     *
     * @return UserConfirmCode|null
     */
    public function getUserConfirmCode(string $code): ?UserConfirmCode;

    /**
     * @param UserConfirmCode $code
     *
     * @return bool
     */
    public function validateIsNotExpired(UserConfirmCode $code): bool;
}
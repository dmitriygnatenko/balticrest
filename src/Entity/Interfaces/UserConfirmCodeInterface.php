<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface UserConfirmCodeInterface
{
    /** @var int Время жизни кода 5 суток */
    public const EXPIRATION_TIME = 432000;

    /** @var int Подтверждение адреса эл почты */
    public const TYPE_CONFIRM_EMAIL = 1;

    /** @var int Восстановление пароля */
    public const TYPE_RESTORE_PASSWORD = 2;
}
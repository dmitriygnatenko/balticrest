<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface UserConfirmCodeInterface
{
    /** @var int Время жизни кода 5 суток*/
    public const EXPIRATION_TIME = 432000;
}
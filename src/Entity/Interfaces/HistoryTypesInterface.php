<?php

declare(strict_types=1);

namespace App\Entity\Interfaces;

interface HistoryTypesInterface
{
    /** @var int */
    public const USER_ADDED = 1;

    /** @var int */
    public const USER_REGISTERED = 2;

    /** @var int */
    public const USER_RESTORED_PASSWORD = 3;
}
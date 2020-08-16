<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Log;

use App\Entity\User;

interface HistoryLoggerInterface
{
    /**
     * @param int $type
     * @param array $context
     * @param User|null $user
     */
    public function log(int $type, array $context = [], ?User $user = null): void;
}
<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Mail;

use App\Entity\User;

interface EmailSenderInterface
{
    /**
     * @param User $user
     */
    public function sendRegistrationConfirmEmail(User $user);

    /**
     * @param User $user
     */
    public function sendRestoreConfirmEmail(User $user);
}
<?php

declare(strict_types=1);

namespace App\Components\Security\Authenticator;

interface AuthenticatorInterface
{
    /** @var string */
    public const LOGIN_ROUTE = 'login';
}
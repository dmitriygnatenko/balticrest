<?php

namespace App\Entity;

interface UserRolesInterface
{
    /** @var string */
    const ROLE_USER = 'ROLE_USER';

    /** @var string  */
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /** @var string */
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
}
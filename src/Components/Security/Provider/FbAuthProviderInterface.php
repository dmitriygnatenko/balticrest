<?php

declare(strict_types=1);

namespace App\Components\Security\Provider;

use App\Components\Security\DTO\FbUserDTO;

interface FbAuthProviderInterface
{
    /**
     * @return string
     */
    public function getAuthUrl(): string;

    /**
     * @param string $code
     *
     * @return FbUserDTO|null
     */
    public function getUser(string $code): ?FbUserDTO;
}
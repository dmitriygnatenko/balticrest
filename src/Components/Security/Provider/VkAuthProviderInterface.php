<?php

declare(strict_types=1);

namespace App\Components\Security\Provider;

use App\Components\Security\DTO\VkUserDTO;

interface VkAuthProviderInterface
{
    /**
     * @return string
     */
    public function getAuthUrl(): string;

    /**
     * @param string $code
     *
     * @return VkUserDTO|null
     */
    public function getUser(string $code): ?VkUserDTO;
}
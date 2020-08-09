<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Auth;

use App\Components\Security\DTO\FbUserDTO;
use App\Components\Security\DTO\VkUserDTO;
use App\Entity\User;

interface AuthManagerInterface
{
    /**
     * @param FbUserDTO $dto
     *
     * @return User
     */
    public function createFbUser(FbUserDTO $dto): ?User;

    /**
     * @param VkUserDTO $dto
     *
     * @return User
     */
    public function createVkUser(VkUserDTO $dto): ?User;

    /**
     * @param string $email
     * @param string $username
     *
     * @return User|null
     */
    public function createNotConfirmedUser(string $email, string $username): ?User;
}
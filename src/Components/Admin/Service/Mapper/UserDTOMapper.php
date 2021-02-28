<?php

declare(strict_types=1);

namespace App\Components\Admin\Service\Mapper;

use App\Components\Admin\Service\DTO\UserDTO;
use App\Entity\User;

class UserDTOMapper
{
    /**
     * @param User $user
     * @param UserDTO $dto
     *
     * @return UserDTO
     */
    public function fill(User $user, UserDTO $dto): UserDTO
    {
        $dto
            ->setId((int) $user->getId())
            ->setEmail((string) $user->getEmail())
            ->setIsActive((bool) $user->getIsActive());

        return $dto;
    }

    /**
     * @param User[] $users
     *
     * @return UserDTO[]
     */
    public function fillAll(array $users): array
    {
        $dtoArray = [];

        foreach ($users as $user) {
            $dtoArray[] = $this->fill($user, new UserDTO());
        }

        return $dtoArray;
    }
}
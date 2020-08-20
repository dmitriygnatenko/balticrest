<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Auth;

use App\Entity\UserConfirmCode;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserConfirmService implements UserConfirmServiceInterface
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $code
     * @param int $type
     *
     * @return UserConfirmCode|null
     */
    public function getUserConfirmCode(string $code, int $type): ?UserConfirmCode
    {
        return $this->entityManager->getRepository(UserConfirmCode::class)
            ->findOneBy(['code' => $code, 'type' => $type]);
    }

    /**
     * @param UserConfirmCode $code
     *
     * @return bool
     */
    public function validateIsNotExpired(UserConfirmCode $code): bool
    {
        if ($code->getCreated() === null) {
            return false;
        }

        return ($code->getCreated()->getTimestamp() + UserConfirmCode::EXPIRATION_TIME) > time();
    }
}
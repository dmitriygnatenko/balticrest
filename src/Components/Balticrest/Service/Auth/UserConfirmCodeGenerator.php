<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Auth;

use App\Entity\User;
use App\Entity\UserConfirmCode;
use Doctrine\ORM\EntityManagerInterface;

class UserConfirmCodeGenerator implements UserConfirmCodeGeneratorInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param int $type
     *
     * @return UserConfirmCode
     */
    public function generate(User $user, int $type): UserConfirmCode
    {
        $confirmCode = $this->entityManager
            ->getRepository(UserConfirmCode::class)
            ->findOneBy(['user' => $user]);

        if ($confirmCode !== null) {
            $this->entityManager->remove($confirmCode);
            $this->entityManager->flush();
        }

        $code = substr(hash('sha512', (string) Rand()), 0, 40);

        $userConfirmCode = (new UserConfirmCode())
            ->setUser($user)
            ->setCode($code)
            ->setType($type);

        $this->entityManager->persist($userConfirmCode);
        $this->entityManager->flush();

        return $userConfirmCode;
    }
}
<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Auth;

use App\Components\Security\DTO\FbUserDTO;
use App\Components\Security\DTO\VkUserDTO;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Throwable;

class UserCreator implements UserCreatorInterface
{
    /** @var Logger */
    private $logger;

    /** @var EntityManager */
    private $entityManager;

    /**
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * @param FbUserDTO $dto
     *
     * @return User
     */
    public function createFbUser(FbUserDTO $dto): ?User
    {
        try {
            $user = new User();

            $user->setFbId($dto->getId())
                ->setUsername($dto->getName())
                ->setEmail($dto->getEmail())
                ->setPassword(null)
                ->setRoles([User::ROLE_USER])
                ->setIsActive(true)
                ->setIsConfirmed(true)
                ->setPhoto($this->getPhotoContent($dto->getPhoto()));

            $this->entityManager->persist($user);
            $this->entityManager->flush($user);

            return $user;
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return null;
        }
    }

    /**
     * @param VkUserDTO $dto
     *
     * @return User
     */
    public function createVkUser(VkUserDTO $dto): ?User
    {
        try {
            $username = trim($dto->getFirstName() . ' ' . $dto->getLastName());

            $user = new User();
            $user->setVkId($dto->getId())
                ->setUsername($username)
                ->setEmail($dto->getEmail())
                ->setPassword(null)
                ->setRoles([User::ROLE_USER])
                ->setIsActive(true)
                ->setIsConfirmed(true)
                ->setPhoto($this->getPhotoContent($dto->getPhoto()));

            $this->entityManager->persist($user);
            $this->entityManager->flush($user);

            return $user;
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return null;
        }
    }

    /**
     * @param string $email
     * @param string $username
     *
     * @return User|null
     */
    public function createNotConfirmedUser(string $email, string $username): ?User
    {
        try {
            $user = new User();
            $user->setUsername($username)
                ->setEmail($email)
                ->setPassword(null)
                ->setRoles([User::ROLE_USER])
                ->setIsActive(true)
                ->setIsConfirmed(false);

            $this->entityManager->persist($user);
            $this->entityManager->flush($user);

            return $user;
        } catch (Throwable $exception) {
            $this->logger->error($exception->getMessage(), ['exception' => $exception]);

            return null;
        }
    }

    /**
     * @param string $url
     *
     * @return string|null
     */
    private function getPhotoContent(string $url): ?string
    {
        if ($url) {
            $photoContent = file_get_contents($url);
            if ($photoContent) {
                return $photoContent;
            }
        }

        return null;
    }
}
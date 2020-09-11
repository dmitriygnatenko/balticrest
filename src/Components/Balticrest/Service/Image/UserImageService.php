<?php

declare(strict_types=1);

namespace App\Components\Balticrest\Service\Image;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;

class UserImageService implements UserImageServiceInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ParameterBagInterface */
    private $parameterBag;

    /** @var Security */
    private $security;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ParameterBagInterface $parameterBag
     * @param Security $security
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->parameterBag = $parameterBag;
        $this->security = $security;
    }

    /**
     * @param int|null $userId
     *
     * @return resource
     */
    public function getUserAvatarContent(int $userId = null)
    {
        if ($userId === null) {
            /** @var User|null $user */
            $user = $this->security->getUser();
        } else {
            $user = $this->entityManager->getRepository(User::class)->find($userId);
        }

        $avatarResource = null;

        if ($user !== null) {
            $avatarResource = $user->getPhoto();
        }

        if ($avatarResource === null) {
            $avatarResource = fopen($this->parameterBag->get('kernel.project_dir') . self::DEFAULT_AVATAR_PATH, 'r');
        }

        return $avatarResource;
    }
}
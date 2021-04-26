<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Interfaces\UserRolesInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var string */
    public const USER = 'user';

    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user
            ->setRoles([UserRolesInterface::ROLE_USER])
            ->setEmail('test@balticrest.ru')
            ->setIsActive(true)
            ->setPassword($this->passwordEncoder->encodePassword($user, 'test'));

        $manager->persist($user);

        $this->addReference(self::USER, $user);

        $manager->flush();
    }
}

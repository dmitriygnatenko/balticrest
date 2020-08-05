<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

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
            ->setRoles([User::ROLE_SUPER_ADMIN])
            ->setEmail('test@balticrest.ru')
            ->setUsername('Super admin')
            ->setIsActive(true)
            ->setIsConfirmed(true)
            ->setPassword($this->passwordEncoder->encodePassword($user, '12345'));

        $manager->persist($user);
        $manager->flush();
    }
}

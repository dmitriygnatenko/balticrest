<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixture extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $ru = (new Language())
            ->setCode('ru')
            ->setTitle('Русский')
            ->setIsActive(true);

        $manager->persist($ru);

        $en = (new Language())
            ->setCode('en')
            ->setTitle('English')
            ->setIsActive(true);

        $manager->persist($en);

        $de = (new Language())
            ->setCode('de')
            ->setTitle('Deutsch')
            ->setIsActive(true);

        $manager->persist($de);

        $pl = (new Language())
            ->setCode('pl')
            ->setTitle('Polski')
            ->setIsActive(true);

        $manager->persist($pl);

        $lt = (new Language())
            ->setCode('lt')
            ->setTitle('Lietuvis')
            ->setIsActive(true);

        $manager->persist($lt);

        $cn = (new Language())
            ->setCode('cn')
            ->setTitle('中文')
            ->setIsActive(true);

        $manager->persist($cn);

        $manager->flush();
    }
}

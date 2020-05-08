<?php

namespace App\DataFixtures;

use App\Entity\PointType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PointTypeFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $hotels = (new PointType())->setCode('hotels');
        $manager->persist($hotels);

        $medicine = (new PointType())->setCode('medicine');
        $manager->persist($medicine);

        $cafes = (new PointType())->setCode('cafes');
        $manager->persist($cafes);

        $shops = (new PointType())->setCode('shops');
        $manager->persist($shops);

        $museums = (new PointType())->setCode('museums');
        $manager->persist($museums);

        $sport = (new PointType())->setCode('sport');
        $manager->persist($sport);

        $transport = (new PointType())->setCode('transport');
        $manager->persist($transport);

        $manager->flush();
    }
}
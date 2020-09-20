<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $zelenogradsk = (new City())
            ->setCode('zelenogradsk')
            ->setImage('/static/balticrest/images/cities/zelenogradsk.png')
            ->setLat(54.957757)
            ->setLon(20.474495)
            ->setZoom(15)
            ->setIsActive(true);

        $manager->persist($zelenogradsk);

        $svetlogorsk = (new City())
            ->setCode('svetlogorsk')
            ->setImage('/static/balticrest/images/cities/svetlogorsk.png')
            ->setLat(54.942050)
            ->setLon(20.153235)
            ->setZoom(15)
            ->setIsActive(true);

        $manager->persist($svetlogorsk);

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\PointType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PointTypeFixtures extends Fixture
{
    /** @var string */
    public const POINT_TYPE_HOTELS = 'point_type_hotels';

    /** @var string */
    public const POINT_TYPE_MEDICINE = 'point_type_medicine';

    /** @var string */
    public const POINT_TYPE_CAFES = 'point_type_cafes';

    /** @var string */
    public const POINT_TYPE_SHOPS = 'point_type_shops';

    /** @var string */
    public const POINT_TYPE_MUSEUMS = 'point_type_museums';

    /** @var string */
    public const POINT_TYPE_SPORT = 'point_type_sport';

    /** @var string */
    public const POINT_TYPE_TRANSPORT = 'point_type_transport';

    /** @var string */
    public const POINT_TYPE_BANKS = 'point_type_banks';

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $hotels = (new PointType())->setCode('hotels');
        $manager->persist($hotels);
        $this->addReference(self::POINT_TYPE_HOTELS, $hotels);

        $medicine = (new PointType())->setCode('medicine');
        $manager->persist($medicine);
        $this->addReference(self::POINT_TYPE_MEDICINE, $medicine);

        $cafes = (new PointType())->setCode('cafes');
        $manager->persist($cafes);
        $this->addReference(self::POINT_TYPE_CAFES, $cafes);

        $shops = (new PointType())->setCode('shops');
        $manager->persist($shops);
        $this->addReference(self::POINT_TYPE_SHOPS, $shops);

        $museums = (new PointType())->setCode('museums');
        $manager->persist($museums);
        $this->addReference(self::POINT_TYPE_MUSEUMS, $museums);

        $sport = (new PointType())->setCode('sport');
        $manager->persist($sport);
        $this->addReference(self::POINT_TYPE_SPORT, $sport);

        $transport = (new PointType())->setCode('transport');
        $manager->persist($transport);
        $this->addReference(self::POINT_TYPE_TRANSPORT, $transport);

        $banks = (new PointType())->setCode('banks');
        $manager->persist($banks);
        $this->addReference(self::POINT_TYPE_BANKS, $banks);

        $manager->flush();
    }
}
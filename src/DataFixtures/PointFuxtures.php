<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Point;
use App\Entity\PointLangData;
use App\Entity\SearchData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class PointFuxtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadHotels($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadHotels(ObjectManager $manager)
    {
        foreach (range(1, 3) as $number) {

            $hotel = (new Point())
                ->setUrl('test-hotel-' . $number)
                ->setIsActive(true)
                ->setLastUpdateTime(new DateTimeImmutable())
                ->setLat('54.9407518335')
                ->setLon('20.1373279095')
                ->setLogo('')
                ->setType($this->getReference(PointTypeFixtures::POINT_TYPE_HOTELS))
                ->setCity($this->getReference(CityFixtures::CITY_SVETLOGORSK))
                ->setData([
                    'email' => 'test-hotel-' . $number . '@test.ru',
                    'phones' => '+11111111111' . $number,
                    'services' => ['restaurant', 'parking'],
                ]);

            $manager->persist($hotel);

            $hotelRu = (new PointLangData())
                ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_RU))
                ->setPoint($hotel)
                ->setTitle('Тестовый отель ' . $number)
                ->setData([
                    'short_desc' => 'Тестовый отель',
                ]);

            $manager->persist($hotelRu);

            $hotelEn = (new PointLangData())
                ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_EN))
                ->setPoint($hotel)
                ->setTitle('Test hotel ' . $number)
                ->setData([
                    'short_desc' => 'Test hotel',
                ]);

            $manager->persist($hotelEn);

            $hotelDe = (new PointLangData())
                ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_DE))
                ->setPoint($hotel)
                ->setTitle('Testhotel ' . $number)
                ->setData([
                    'short_desc' => 'Testhotel',
                ]);

            $manager->persist($hotelDe);

            $hotelPl = (new PointLangData())
                ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_PL))
                ->setPoint($hotel)
                ->setTitle('Hotel testowy ' . $number)
                ->setData([
                    'short_desc' => 'Hotel testowy',
                ]);

            $manager->persist($hotelPl);

            $hotelLt = (new PointLangData())
                ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_LT))
                ->setPoint($hotel)
                ->setTitle('Bandomasis viešbutis ' . $number)
                ->setData([
                    'short_desc' => 'Bandomasis viešbutis',
                ]);

            $manager->persist($hotelLt);

            $hotelCn = (new PointLangData())
                ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_CN))
                ->setPoint($hotel)
                ->setTitle('測試酒店 ' . $number)
                ->setData([
                    'short_desc' => '測試酒店',
                ]);

            $manager->persist($hotelCn);

            $manager->flush();

            $searchData = (new SearchData())
                ->setObjectId($hotelRu->getId())
                ->setObjectTypeId(SearchData::TYPE_POINT_ID)
                ->setData('xxxxx Тестовый отель xxxxx');

            $manager->persist($searchData);

            $manager->flush();
        }
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            CityFixtures::class,
            LanguageFixture::class,
            PointTypeFixtures::class,
        ];
    }
}
<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LanguageFixture extends Fixture
{
    /** @var string */
    public const LANGUAGE_RU = 'language_ru';

    /** @var string */
    public const LANGUAGE_EN = 'language_en';

    /** @var string */
    public const LANGUAGE_DE = 'language_de';

    /** @var string */
    public const LANGUAGE_PL = 'language_pl';

    /** @var string */
    public const LANGUAGE_LT = 'language_lt';

    /** @var string */
    public const LANGUAGE_CN = 'language_cn';

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

        $this->addReference(self::LANGUAGE_RU, $ru);

        $en = (new Language())
            ->setCode('en')
            ->setTitle('English')
            ->setIsActive(true);

        $manager->persist($en);

        $this->addReference(self::LANGUAGE_EN, $en);

        $de = (new Language())
            ->setCode('de')
            ->setTitle('Deutsch')
            ->setIsActive(true);

        $manager->persist($de);

        $this->addReference(self::LANGUAGE_DE, $de);

        $pl = (new Language())
            ->setCode('pl')
            ->setTitle('Polski')
            ->setIsActive(true);

        $manager->persist($pl);

        $this->addReference(self::LANGUAGE_PL, $pl);

        $lt = (new Language())
            ->setCode('lt')
            ->setTitle('Lietuvis')
            ->setIsActive(true);

        $manager->persist($lt);

        $this->addReference(self::LANGUAGE_LT, $lt);

        $cn = (new Language())
            ->setCode('cn')
            ->setTitle('中文')
            ->setIsActive(true);

        $manager->persist($cn);

        $this->addReference(self::LANGUAGE_CN, $cn);

        $manager->flush();
    }
}

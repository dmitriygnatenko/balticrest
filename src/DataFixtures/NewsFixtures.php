<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Language;
use App\Entity\News;
use App\Entity\NewsLangData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class NewsFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $news = (new News())
            ->setImage('test.jpg')
            ->setPublishDate(new DateTimeImmutable())
            ->setIsActive(true);

        $manager->persist($news);

        $newsLangDataRu = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Тестовая новость')
            ->setText('Тестовый текст новости')
            ->setLanguage($manager->getRepository(Language::class)->findOneByCode('ru'));

        $manager->persist($newsLangDataRu);

        $newsLangDataEn = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Test news')
            ->setText('Test news text')
            ->setLanguage($manager->getRepository(Language::class)->findOneByCode('en'));

        $manager->persist($newsLangDataEn);

        $newsLangDataDe = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Testnachrichten')
            ->setText('Testen Sie den Nachrichtentext')
            ->setLanguage($manager->getRepository(Language::class)->findOneByCode('de'));

        $manager->persist($newsLangDataDe);

        $newsLangDataPl = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Wiadomości testowe')
            ->setText('Tekst wiadomości testowej')
            ->setLanguage($manager->getRepository(Language::class)->findOneByCode('pl'));

        $manager->persist($newsLangDataPl);

        $newsLangDataLt = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Testo naujienos')
            ->setText('Išbandykite naujienų tekstą')
            ->setLanguage($manager->getRepository(Language::class)->findOneByCode('lt'));

        $manager->persist($newsLangDataLt);

        $newsLangDataCn = (new NewsLangData())
            ->setNews($news)
            ->setTitle('測試新聞')
            ->setText('測試新聞文字')
            ->setLanguage($manager->getRepository(Language::class)->findOneByCode('cn'));

        $manager->persist($newsLangDataCn);

        $manager->flush();
    }
}
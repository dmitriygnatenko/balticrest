<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\News;
use App\Entity\NewsLangData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class NewsFixtures extends Fixture implements DependentFixtureInterface
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
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_RU));

        $manager->persist($newsLangDataRu);

        $newsLangDataEn = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Test news')
            ->setText('Test news text')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_EN));

        $manager->persist($newsLangDataEn);

        $newsLangDataDe = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Testnachrichten')
            ->setText('Testen Sie den Nachrichtentext')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_DE));

        $manager->persist($newsLangDataDe);

        $newsLangDataPl = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Wiadomości testowe')
            ->setText('Tekst wiadomości testowej')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_PL));

        $manager->persist($newsLangDataPl);

        $newsLangDataLt = (new NewsLangData())
            ->setNews($news)
            ->setTitle('Testo naujienos')
            ->setText('Išbandykite naujienų tekstą')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_LT));

        $manager->persist($newsLangDataLt);

        $newsLangDataCn = (new NewsLangData())
            ->setNews($news)
            ->setTitle('測試新聞')
            ->setText('測試新聞文字')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_CN));

        $manager->persist($newsLangDataCn);

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            LanguageFixture::class,
        ];
    }
}
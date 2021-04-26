<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\ArticleLangData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadContactsArticle($manager);
        $this->loadPrivacyPolicyArticle($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadContactsArticle(ObjectManager $manager)
    {
        $contacts = (new Article())
            ->setUrl('contacts')
            ->setLastUpdateTime(new DateTimeImmutable())
            ->setIsActive(true);

        $manager->persist($contacts);

        $contactsRu = (new ArticleLangData())
            ->setArticle($contacts)
            ->setTitle('Контактная информация')
            ->setText('Контактная информация')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_RU));

        $manager->persist($contactsRu);

        $contactsEn = (new ArticleLangData())
            ->setArticle($contacts)
            ->setTitle('Contacts')
            ->setText('Contacts')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_EN));

        $manager->persist($contactsEn);

        $contactsDe = (new ArticleLangData())
            ->setArticle($contacts)
            ->setTitle('Kontakte')
            ->setText('Kontakte')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_DE));

        $manager->persist($contactsDe);

        $contactsPl = (new ArticleLangData())
            ->setArticle($contacts)
            ->setTitle('Łączność')
            ->setText('Łączność')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_PL));

        $manager->persist($contactsPl);

        $contactsLt = (new ArticleLangData())
            ->setArticle($contacts)
            ->setTitle('Kontaktai')
            ->setText('Kontaktai')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_LT));

        $manager->persist($contactsLt);

        $contactsCn = (new ArticleLangData())
            ->setArticle($contacts)
            ->setTitle('聯絡人')
            ->setText('聯絡人')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_CN));

        $manager->persist($contactsCn);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    private function loadPrivacyPolicyArticle(ObjectManager $manager)
    {
        $privacyPolicy = (new Article())
            ->setUrl('privacy-policy')
            ->setLastUpdateTime(new DateTimeImmutable())
            ->setIsActive(true);

        $manager->persist($privacyPolicy);

        $privacyPolicyRu = (new ArticleLangData())
            ->setArticle($privacyPolicy)
            ->setTitle('Политика конфиденциальности')
            ->setText('Политика конфиденциальности')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_RU));

        $manager->persist($privacyPolicyRu);

        $privacyPolicyEn = (new ArticleLangData())
            ->setArticle($privacyPolicy)
            ->setTitle('Privacy policy')
            ->setText('Privacy policy')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_EN));

        $manager->persist($privacyPolicyEn);

        $privacyPolicyDe = (new ArticleLangData())
            ->setArticle($privacyPolicy)
            ->setTitle('Datenschutz-Bestimmungen')
            ->setText('Datenschutz-Bestimmungen')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_DE));

        $manager->persist($privacyPolicyDe);

        $privacyPolicyPl = (new ArticleLangData())
            ->setArticle($privacyPolicy)
            ->setTitle('Polityka prywatności')
            ->setText('Polityka prywatności')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_PL));

        $manager->persist($privacyPolicyPl);

        $privacyPolicyLt = (new ArticleLangData())
            ->setArticle($privacyPolicy)
            ->setTitle('Privatumo politika')
            ->setText('Privatumo politika')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_LT));

        $manager->persist($privacyPolicyLt);

        $privacyPolicyCn = (new ArticleLangData())
            ->setArticle($privacyPolicy)
            ->setTitle('隱私政策')
            ->setText('隱私政策')
            ->setMetaDescription('')
            ->setMetaKeywords('')
            ->setLanguage($this->getReference(LanguageFixture::LANGUAGE_CN));

        $manager->persist($privacyPolicyCn);

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
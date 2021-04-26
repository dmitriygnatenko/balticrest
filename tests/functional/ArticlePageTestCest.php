<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class ArticlePageTestCest
{
    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewContactsRu(FunctionalTester $I)
    {
        $I->amOnPage('/contacts');
        $I->wantTo('View contacts page (RU)');
        $I->seePageIsAvailable();
        $I->see('Контактная информация');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewContactsEn(FunctionalTester $I)
    {
        $I->amOnPage('/en/contacts');
        $I->wantTo('View contacts page (EN)');
        $I->seePageIsAvailable();
        $I->see('Contacts');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewContactsDe(FunctionalTester $I)
    {
        $I->amOnPage('/de/contacts');
        $I->wantTo('View contacts page (DE)');
        $I->seePageIsAvailable();
        $I->see('Kontakte');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewContactsPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl/contacts');
        $I->wantTo('View contacts page (PL)');
        $I->seePageIsAvailable();
        $I->see('Łączność');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewContactsLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt/contacts');
        $I->wantTo('View contacts page (LT)');
        $I->seePageIsAvailable();
        $I->see('Kontaktai');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewContactsCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn/contacts');
        $I->wantTo('View contacts page (CN)');
        $I->seePageIsAvailable();
        $I->see('聯絡人');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPrivacyPolicyRu(FunctionalTester $I)
    {
        $I->amOnPage('/privacy-policy');
        $I->wantTo('View privacy-policy page (RU)');
        $I->seePageIsAvailable();
        $I->see('Политика конфиденциальности');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPrivacyPolicyEn(FunctionalTester $I)
    {
        $I->amOnPage('/en/privacy-policy');
        $I->wantTo('View privacy-policy page (EN)');
        $I->seePageIsAvailable();
        $I->see('Privacy policy');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPrivacyPolicyDe(FunctionalTester $I)
    {
        $I->amOnPage('/de/privacy-policy');
        $I->wantTo('View privacy-policy page (DE)');
        $I->seePageIsAvailable();
        $I->see('Datenschutz-Bestimmungen');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPrivacyPolicyPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl/privacy-policy');
        $I->wantTo('View privacy-policy page (PL)');
        $I->seePageIsAvailable();
        $I->see('Polityka prywatności');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPrivacyPolicyLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt/privacy-policy');
        $I->wantTo('View privacy-policy page (LT)');
        $I->seePageIsAvailable();
        $I->see('Privatumo politika');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPrivacyPolicyCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn/privacy-policy');
        $I->wantTo('View privacy-policy page (CN)');
        $I->seePageIsAvailable();
        $I->see('隱私政策');
    }
}

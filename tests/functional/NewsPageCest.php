<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class NewsPageCest
{
    /**
     * @param FunctionalTester $I
     */
    public function viewRu(FunctionalTester $I)
    {
        $I->amOnPage('/news');
        $I->wantTo('View news page (RU)');
        $I->seePageIsAvailable();
        $I->see('Тестовый текст новости');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewEn(FunctionalTester $I)
    {
        $I->amOnPage('/en/news');
        $I->wantTo('View news page (EN)');
        $I->seePageIsAvailable();
        $I->see('Test news text');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewDe(FunctionalTester $I)
    {
        $I->amOnPage('/de/news');
        $I->wantTo('View news page (DE)');
        $I->seePageIsAvailable();
        $I->see('Testen Sie den Nachrichtentext');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl/news');
        $I->wantTo('View news page (PL)');
        $I->seePageIsAvailable();
        $I->see('Tekst wiadomości testowej');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt/news');
        $I->wantTo('View news page (LT)');
        $I->seePageIsAvailable();
        $I->see('Išbandykite naujienų tekstą');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn/news');
        $I->wantTo('View news page (CN)');
        $I->seePageIsAvailable();
        $I->see('測試新聞文字');
    }
}

<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class MapPageTestCest
{
    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewRu(FunctionalTester $I)
    {
        $I->amOnPage('/svetlogorsk');
        $I->wantTo('View map page (RU)');
        $I->seePageIsAvailable();
        $I->seeInTitle('карта');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewEn(FunctionalTester $I)
    {
        $I->amOnPage('/en/svetlogorsk');
        $I->wantTo('View map page (EN)');
        $I->seePageIsAvailable();
        $I->seeInTitle('map');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewDe(FunctionalTester $I)
    {
        $I->amOnPage('/de/svetlogorsk');
        $I->wantTo('View map page (DE)');
        $I->seePageIsAvailable();
        $I->seeInTitle('Karte');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl/svetlogorsk');
        $I->wantTo('View map page (PL)');
        $I->seePageIsAvailable();
        $I->seeInTitle('mapa');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt/svetlogorsk');
        $I->wantTo('View map page (LT)');
        $I->seePageIsAvailable();
        $I->seeInTitle('žemėlapis');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn/svetlogorsk');
        $I->wantTo('View map page (CN)');
        $I->seePageIsAvailable();
        $I->seeInTitle('地圖');
    }
}

<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class PointListPageTestCest
{
    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewListRu(FunctionalTester $I)
    {
        $I->amOnPage('/svetlogorsk/hotels/list');
        $I->wantTo('View list page (RU)');
        $I->seePageIsAvailable();
        $I->see('Тестовый отель 1');
        $I->see('Тестовый отель 2');
        $I->see('Тестовый отель 3');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewListEn(FunctionalTester $I)
    {
        $I->amOnPage('/en/svetlogorsk/hotels/list');
        $I->wantTo('View list page (EN)');
        $I->seePageIsAvailable();
        $I->see('Test hotel 1');
        $I->see('Test hotel 2');
        $I->see('Test hotel 3');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewListDe(FunctionalTester $I)
    {
        $I->amOnPage('/de/svetlogorsk/hotels/list');
        $I->wantTo('View list page (DE)');
        $I->seePageIsAvailable();
        $I->see('Testhotel 1');
        $I->see('Testhotel 2');
        $I->see('Testhotel 3');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewListPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl/svetlogorsk/hotels/list');
        $I->wantTo('View list page (PL)');
        $I->seePageIsAvailable();
        $I->see('Hotel testowy 1');
        $I->see('Hotel testowy 2');
        $I->see('Hotel testowy 3');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewListLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt/svetlogorsk/hotels/list');
        $I->wantTo('View list page (LT)');
        $I->seePageIsAvailable();
        $I->see('Bandomasis viešbutis 1');
        $I->see('Bandomasis viešbutis 2');
        $I->see('Bandomasis viešbutis 3');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewListCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn/svetlogorsk/hotels/list');
        $I->wantTo('View list page (CN)');
        $I->seePageIsAvailable();
        $I->see('測試酒店 1');
        $I->see('測試酒店 2');
        $I->see('測試酒店 3');
    }
}

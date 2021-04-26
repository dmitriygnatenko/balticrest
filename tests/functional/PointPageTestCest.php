<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class PointPageTestCest
{
    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewRu(FunctionalTester $I)
    {
        $I->amOnPage('/svetlogorsk/hotels/point/test-hotel-1');
        $I->wantTo('View point page (RU)');
        $I->seePageIsAvailable();
        $I->see('Тестовый отель 1');
        $I->see('test-hotel-1@test.ru');
        $I->see('+111111111111');
        $I->see('парковка');
        $I->see('ресторан');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewEn(FunctionalTester $I)
    {
        $I->amOnPage('/en/svetlogorsk/hotels/point/test-hotel-1');
        $I->wantTo('View point page (EN)');
        $I->seePageIsAvailable();
        $I->see('Test hotel 1');
        $I->see('test-hotel-1@test.ru');
        $I->see('+11111111111');
        $I->see('parking');
        $I->see('restaurant');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewDe(FunctionalTester $I)
    {
        $I->amOnPage('/de/svetlogorsk/hotels/point/test-hotel-1');
        $I->wantTo('View point page (DE)');
        $I->seePageIsAvailable();
        $I->see('Testhotel 1');
        $I->see('test-hotel-1@test.ru');
        $I->see('+111111111111');
        $I->see('Parken');
        $I->see('Restaurant');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl/svetlogorsk/hotels/point/test-hotel-1');
        $I->wantTo('View point page (PL)');
        $I->seePageIsAvailable();
        $I->see('Hotel testowy 1');
        $I->see('test-hotel-1@test.ru');
        $I->see('+111111111111');
        $I->see('parking');
        $I->see('restauracja');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt/svetlogorsk/hotels/point/test-hotel-1');
        $I->wantTo('View point page (LT)');
        $I->seePageIsAvailable();
        $I->see('Bandomasis viešbutis 1');
        $I->see('test-hotel-1@test.ru');
        $I->see('+111111111111');
        $I->see('automobilių stovėjimo aikštelė');
        $I->see('restoranas');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn/svetlogorsk/hotels/point/test-hotel-1');
        $I->wantTo('View point page (CN)');
        $I->seePageIsAvailable();
        $I->see('測試酒店 1');
        $I->see('test-hotel-1@test.ru');
        $I->see('+111111111111');
        $I->see('餐廳');
        $I->see('停車');
    }
}

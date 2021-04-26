<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class SearchTestCest
{
    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function search(FunctionalTester $I)
    {
        $I->amOnPage('/svetlogorsk');
        $I->wantTo('Test search for hotel');
        $I->fillField(['name' => 'q'], 'Тестовый отель');
        $I->click('div.search button');
        $I->seeCurrentUrlMatches('/search/');
        $I->see('Результаты поиска');
        $I->see('Тестовый отель 1');
        $I->see('Тестовый отель 2');
        $I->see('Тестовый отель 3');
    }
}

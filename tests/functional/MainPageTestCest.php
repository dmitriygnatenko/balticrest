<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class MainPageTestCest
{
    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewRu(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->wantTo('View main page (RU)');
        $I->seePageIsAvailable();
        $I->see('ТУРИСТИЧЕСКИЙ СПРАВОЧНИК БАЛТИЙСКОГО ПОБЕРЕЖЬЯ');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewEn(FunctionalTester $I)
    {
        $I->amOnPage('/en');
        $I->wantTo('View main page (EN)');
        $I->seePageIsAvailable();
        $I->see('TOURIST GUIDE TO THE BALTIC COAST');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewDe(FunctionalTester $I)
    {
        $I->amOnPage('/de');
        $I->wantTo('View main page (DE)');
        $I->seePageIsAvailable();
        $I->see('REISEFÜHRER AN DER OSTSEEKÜSTE');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl');
        $I->wantTo('View main page (PL)');
        $I->seePageIsAvailable();
        $I->see('PRZEWODNIK TURYSTYCZNY PO WYBRZEŻU BAŁTYKU');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt');
        $I->wantTo('View main page (LT)');
        $I->seePageIsAvailable();
        $I->see('BALTIJOS PAKRANTĖS TURISTINIS VADOVAS');
    }

    /**
     * @param \App\Tests\FunctionalTester $I
     */
    public function viewCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn');
        $I->wantTo('View main page (CN)');
        $I->seePageIsAvailable();
        $I->see('波羅的海沿岸旅遊指南');
    }
}

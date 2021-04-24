<?php

namespace App\Tests;

use App\Tests\FunctionalTester;

class PagesCest
{
    public function mainPage(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->wantTo('View main page (RU)');
        $I->seeResponseCodeIs(200);
        $I->see('ТУРИСТИЧЕСКИЙ СПРАВОЧНИК БАЛТИЙСКОГО ПОБЕРЕЖЬЯ');
    }

    public function mainPageEn(FunctionalTester $I)
    {
        $I->amOnPage('/en');
        $I->wantTo('View main page (EN)');
        $I->see('TOURIST GUIDE TO THE BALTIC COAST');
    }

    public function mainPageDe(FunctionalTester $I)
    {
        $I->amOnPage('/de');
        $I->wantTo('View main page (DE)');
        $I->see('REISEFÜHRER AN DER OSTSEEKÜSTE');
    }

    public function mainPagePl(FunctionalTester $I)
    {
        $I->amOnPage('/pl');
        $I->wantTo('View main page (PL)');
        $I->see('PRZEWODNIK TURYSTYCZNY PO WYBRZEŻU BAŁTYKU');
    }

    public function mainPageLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt');
        $I->wantTo('View main page (LT)');
        $I->see('BALTIJOS PAKRANTĖS TURISTINIS VADOVAS');
    }

    public function mainPageCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn');
        $I->wantTo('View main page (CN)');
        $I->see('波羅的海沿岸旅遊指南');
    }
}

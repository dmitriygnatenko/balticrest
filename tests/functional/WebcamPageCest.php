<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\FunctionalTester;

class WebcamPageCest
{
    /**
     * @param FunctionalTester $I
     */
    public function viewRu(FunctionalTester $I)
    {
        $I->amOnPage('/webcams');
        $I->wantTo('View webcams page (RU)');
        $I->seePageIsAvailable();
        $I->see('Вебкамеры');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewEn(FunctionalTester $I)
    {
        $I->amOnPage('/en/webcams');
        $I->wantTo('View webcams page (EN)');
        $I->seePageIsAvailable();
        $I->see('Webcams');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewDe(FunctionalTester $I)
    {
        $I->amOnPage('/de/webcams');
        $I->wantTo('View webcams page (DE)');
        $I->seePageIsAvailable();
        $I->see('Webcams');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewPl(FunctionalTester $I)
    {
        $I->amOnPage('/pl/webcams');
        $I->wantTo('View webcams page (PL)');
        $I->seePageIsAvailable();
        $I->see('Kamery internetowe');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewLt(FunctionalTester $I)
    {
        $I->amOnPage('/lt/webcams');
        $I->wantTo('View webcams page (LT)');
        $I->seePageIsAvailable();
        $I->see('Interneto kameros');
    }

    /**
     * @param FunctionalTester $I
     */
    public function viewCn(FunctionalTester $I)
    {
        $I->amOnPage('/cn/webcams');
        $I->wantTo('View webcams page (CN)');
        $I->seePageIsAvailable();
        $I->see('網路攝影機');
    }
}

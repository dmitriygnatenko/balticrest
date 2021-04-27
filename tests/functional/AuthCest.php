<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Interfaces\UserRolesInterface;
use App\Tests\FunctionalTester;

class AuthCest
{
    /**
     * @param FunctionalTester $I
     */
    public function testForbidden(FunctionalTester $I)
    {
        $I->amOnPage('/admin');
        $I->wantTo('Test redirect to login page');
        $I->seePageRedirectsTo('/admin', '/login');
        $I->dontSeeInTitle('Администрирование');
    }

    /**
     * @param FunctionalTester $I
     */
    public function testAuth(FunctionalTester $I)
    {
        $I->amOnPage('/admin');
        $I->wantTo('Test auth');
        $I->fillField(['name' => 'email'], 'test@balticrest.ru');
        $I->fillField(['name' => 'password'], 'test');
        $I->click('div.login_form button');
        $I->seeInTitle('Администрирование');
        $I->am(UserRolesInterface::ROLE_USER);
    }
}

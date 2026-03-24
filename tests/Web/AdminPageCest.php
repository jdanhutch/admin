<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class AdminPageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('admin page works.');
        $I->amOnPage('/admin');
        $I->expectTo('see page admin.');
        $I->see('Welcome to the admin page!');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('admin page\'s home button works');
        $I->amOnPage('/admin');
        $I->expectTo('go to the home page from the admin page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function personManagementButton(WebTester $I): void
    {
        $I->wantTo('admin page\'s person management button works');
        $I->amOnPage('/admin');
        $I->expectTo('go to the person management page from the admin page');
        $I->click('Manage person records');
        $I->seeCurrentUrlEquals('/person-admin');
    }

    public function choreManagementButton(WebTester $I): void
    {
        $I->wantTo('admin page\'s chore management button works');
        $I->amOnPage('/admin');
        $I->expectTo('go to the chore management page from the admin page');
        $I->click('Manage chore records');
        $I->seeCurrentUrlEquals('/chore-admin');
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class PersonChoresPageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('person chores page works.');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->expectTo('see page person chores.');
        $I->see('John Doe');
        $I->see('Chores', '.breadcrumb-item.active');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('person chores page\'s home button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->expectTo('go to the home page from the person chores page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('person chores page\'s admin button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->expectTo('go to the admin page from the person chores page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }

    public function personAdminPageButton(WebTester $I): void
    {
        $I->wantTo('person chores page\'s person admin button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->expectTo('go to the person admin page from the person chores page');
        $I->click('Person Admin');
        $I->seeCurrentUrlEquals('/person-admin');
    }

    public function personButton(WebTester $I): void
    {
        $I->wantTo('person chores page\'s person button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->expectTo('go to the view person page from the person chores page');
        $I->click('John Doe');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
    }
}

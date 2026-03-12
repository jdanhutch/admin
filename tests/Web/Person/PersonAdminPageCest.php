<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class PersonAdminPageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('person admin page works.');
        $I->amOnPage('/person-admin');
        $I->expectTo('see page person admin.');
        $I->see('Person Admin');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('person admin page\'s home button works');
        $I->amOnPage('/person-admin');
        $I->expectTo('go to the home page from the person admin page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('person admin page\'s admin button works');
        $I->amOnPage('/person-admin');
        $I->expectTo('go to the admin page from the person admin page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }

    public function createPersonButton(WebTester $I): void
    {
        $I->wantTo('person admin page\'s create person button works');
        $I->amOnPage('/person-admin');
        $I->expectTo('go to the create person page from the person admin page');
        $I->click('Create person');
        $I->seeCurrentUrlEquals('/person-admin/new/edit');
    }

    public function viewPersonButton(WebTester $I): void
    {
        $I->wantTo('person admin page\'s view person button works');
        $I->amOnPage('/person-admin');
        $I->expectTo('go to the view person page from the person admin page');
        $I->click('View');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
    }

    public function editPersonButton(WebTester $I): void
    {
        $I->wantTo('person admin page\'s edit person button works');
        $I->amOnPage('/person-admin');
        $I->expectTo('go to the edit person page from the person admin page');
        $I->click('Edit');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
    }
}

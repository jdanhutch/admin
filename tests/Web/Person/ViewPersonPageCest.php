<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class ViewPersonPageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('view person page works.');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->expectTo('see page view person.');
        $I->see('John Doe');
        $I->see('Edit');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('view person page\'s home button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->expectTo('go to the home page from the view person page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('view person page\'s admin button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->expectTo('go to the admin page from the view person page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }

    public function personAdminPageButton(WebTester $I): void
    {
        $I->wantTo('view person page\'s person admin button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->expectTo('go to the person admin page from the view person page');
        $I->click('Person Admin');
        $I->seeCurrentUrlEquals('/person-admin');
    }

    public function choresButton(WebTester $I): void
    {
        $I->wantTo('view person page\'s chore button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->expectTo('go to the person\'s chore page from the view person page');
        $I->click('Mark Chores');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
    }

    public function editPersonButton(WebTester $I): void
    {
        $I->wantTo('view person page\'s edit button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->expectTo('go to the edit person page from the view person page');
        $I->click('Edit');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
    }

    public function deletePersonButton(WebTester $I): void
    {
        $I->wantTo('view person page\'s delete button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->expectTo('delete the database record and go to the person admin page');
        $I->click('Delete');
        $I->seeCurrentUrlEquals('/person-admin');
        $I->dontSeeInDatabase('public.person', ['id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6']);
    }
}

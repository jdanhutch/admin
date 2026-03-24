<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class EditPersonPageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('edit person page works.');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
        $I->expectTo('see page edit person.');
        $I->see('John Doe');
        $I->see('Cancel');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('edit person page\'s home button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
        $I->expectTo('go to the home page from the edit person page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('edit person page\'s admin button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
        $I->expectTo('go to the admin page from the edit person page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }

    public function personAdminPageButton(WebTester $I): void
    {
        $I->wantTo('edit person page\'s person admin button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
        $I->expectTo('go to the person admin page from the edit person page');
        $I->click('Person Admin');
        $I->seeCurrentUrlEquals('/person-admin');
    }

    public function cancelButton(WebTester $I): void
    {
        $I->wantTo('edit person page\'s edit button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
        $I->expectTo('go to the view person page from the edit person page');
        $I->fillField('Name', 'Changed');
        $I->click('Cancel');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->seeInDatabase('public.person', ['id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'name' => 'John Doe']);
    }

    public function saveButton(WebTester $I): void
    {
        $I->wantTo('edit person page\'s save button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/edit');
        $I->expectTo('update the database record and go to the person admin page');
        $I->fillField('Name', 'Changed');
        $I->click('Save');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
        $I->seeInDatabase('public.person', ['id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'name' => 'Changed']);
    }
}

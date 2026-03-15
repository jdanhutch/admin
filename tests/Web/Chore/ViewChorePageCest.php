<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class ViewChorePageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('view chore page works.');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->expectTo('see page view chore.');
        $I->see('John Doe');
        $I->see('Edit');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('view chore page\'s home button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->expectTo('go to the home page from the view chore page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('view chore page\'s admin button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->expectTo('go to the admin page from the view chore page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }

    public function choreAdminPageButton(WebTester $I): void
    {
        $I->wantTo('view chore page\'s chore admin button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->expectTo('go to the chore admin page from the view chore page');
        $I->click('Chore Admin');
        $I->seeCurrentUrlEquals('/chore-admin');
    }

    public function personButton(WebTester $I): void
    {
        $I->wantTo('view chore page\'s person button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->expectTo('go to the view person page from the view chore page');
        $I->click('John Doe');
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6');
    }

    public function editChoreButton(WebTester $I): void
    {
        $I->wantTo('view chore page\'s edit button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->expectTo('go to the edit chore page from the view chore page');
        $I->click('Edit');
        $I->seeCurrentUrlEquals('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
    }

    public function deleteChoreButton(WebTester $I): void
    {
        $I->wantTo('view chore page\'s delete button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->expectTo('delete the database record and go to the chore admin page');
        $I->click('Delete');
        $I->seeCurrentUrlEquals('/chore-admin');
        $I->dontSeeInDatabase('public.chore', ['id' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587']);
    }
}

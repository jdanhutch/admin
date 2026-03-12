<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class ChoreAdminPageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('chore admin page works.');
        $I->amOnPage('/chore-admin');
        $I->expectTo('see page chore admin.');
        $I->see('Chore Admin');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('chore admin page\'s home button works');
        $I->amOnPage('/chore-admin');
        $I->expectTo('go to the home page from the chore admin page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('chore admin page\'s admin button works');
        $I->amOnPage('/chore-admin');
        $I->expectTo('go to the admin page from the chore admin page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }

    public function createChoreButton(WebTester $I): void
    {
        $I->wantTo('chore admin page\'s create chore button works');
        $I->amOnPage('/chore-admin');
        $I->expectTo('go to the create chore page from the chore admin page');
        $I->click('Create chore');
        $I->seeCurrentUrlEquals('/chore-admin/new/edit');
    }

    public function viewChoreButton(WebTester $I): void
    {
        $I->wantTo('chore admin page\'s view chore button works');
        $I->amOnPage('/chore-admin');
        $I->expectTo('go to the view chore page from the chore admin page');
        $I->click('View');
        $I->seeCurrentUrlEquals('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
    }

    public function editChoreButton(WebTester $I): void
    {
        $I->wantTo('chore admin page\'s edit chore button works');
        $I->amOnPage('/chore-admin');
        $I->expectTo('go to the edit chore page from the chore admin page');
        $I->click('Edit');
        $I->seeCurrentUrlEquals('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
    }
}

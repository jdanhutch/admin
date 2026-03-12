<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class EditChorePageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('edit chore page works.');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
        $I->expectTo('see page edit chore.');
        $I->see('Jane Doe');
        $I->see('Cancel');
    }

    public function homePageButton(WebTester $I): void
    {
        $I->wantTo('edit chore page\'s home button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
        $I->expectTo('go to the home page from the edit chore page');
        $I->click('Home');
        $I->seeCurrentUrlEquals('/');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('edit chore page\'s admin button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
        $I->expectTo('go to the admin page from the edit chore page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }

    public function choreAdminPageButton(WebTester $I): void
    {
        $I->wantTo('edit chore page\'s chore admin button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
        $I->expectTo('go to the chore admin page from the edit chore page');
        $I->click('Chore Admin');
        $I->seeCurrentUrlEquals('/chore-admin');
    }

    public function cancelButton(WebTester $I): void
    {
        $I->wantTo('edit chore page\'s cancel button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
        $I->expectTo('go to the view chore page from the edit chore page');
        $I->fillField('Name', 'Updated');
        $I->click('Cancel');
        $I->seeCurrentUrlEquals('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->seeInDatabase('public.chore', ['id' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587', 'name' => 'Do the laundry', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => false]);
    }

    public function saveButton(WebTester $I): void
    {
        $I->wantTo('edit chore page\'s save button works');
        $I->amOnPage('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/edit');
        $I->expectTo('update the database record and go to the chore admin page');
        $I->fillField('Name', 'Updated');
        $I->click('Save');
        $I->seeCurrentUrlEquals('/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587');
        $I->seeInDatabase('public.chore', ['id' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587', 'name' => 'Updated', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => false]);
    }
}

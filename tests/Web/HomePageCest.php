<?php

declare(strict_types=1);

namespace App\Tests\Web;

use App\Tests\Support\WebTester;

final class HomePageCest
{
    public function base(WebTester $I): void
    {
        $I->wantTo('home page works.');
        $I->amOnPage('/');
        $I->expectTo('see page home.');
        $I->see('Hello!');
    }

    public function adminPageButton(WebTester $I): void
    {
        $I->wantTo('home page\'s admin button works');
        $I->amOnPage('/');
        $I->expectTo('go to the admin page from the home page');
        $I->click('Admin');
        $I->seeCurrentUrlEquals('/admin');
    }
}

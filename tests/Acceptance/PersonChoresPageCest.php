<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Tests\Support\AcceptanceTester;

final class PersonChoresPageCest
{
    public function doneButton(AcceptanceTester $I): void
    {
        $I->wantTo('person chores page\'s done button works');
        $I->amOnPage('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->expectTo('update the database record and stay on the page');

        // Checkboxes initially unchecked
        $I->seeInDatabase('public.chore', ['id' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => false]);
        $I->seeInDatabase('public.chore', ['id' => '019cd5ce-0b7c-7373-8514-256dad0fe4da', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => false]);
        $I->seeNumberOfElements('.chore-checkbox:checked', 0);
        $I->seeNumberOfElements('.chore-checkbox:not(:checked)', 2);

        // Mark chore done
        $I->click('tr:nth-child(1) .chore-checkbox');
        $I->waitForElementClickable('tr:nth-child(1) .chore-checkbox', 5);
        $I->seeNumberOfElements('.chore-checkbox:checked', 1); // clicked chore is done
        $I->seeNumberOfElements('.chore-checkbox:not(:checked)', 1); // other chore is not done
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->seeInDatabase('public.chore', ['id' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => true]);
        $I->seeInDatabase('public.chore', ['id' => '019cd5ce-0b7c-7373-8514-256dad0fe4da', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => false]);

        // Mark chore not done
        $I->click('tr:nth-child(1) .chore-checkbox');
        $I->waitForElementClickable('tr:nth-child(1) .chore-checkbox', 5);
        $I->seeCurrentUrlEquals('/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores');
        $I->seeNumberOfElements('.chore-checkbox:checked', 0);
        $I->seeNumberOfElements('.chore-checkbox:not(:checked)', 2);
        $I->seeInDatabase('public.chore', ['id' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => false]);
        $I->seeInDatabase('public.chore', ['id' => '019cd5ce-0b7c-7373-8514-256dad0fe4da', 'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6', 'done' => false]);
    }
}

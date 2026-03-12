<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use HttpSoft\Message\ServerRequest;
use Ramsey\Uuid\Uuid;

use function PHPUnit\Framework\assertSame;

final class DeletePersonCest
{
    #[DataProvider('_deleteData')]
    public function delete(FunctionalTester $tester, Example $example): void
    {
        $tester->seeNumRecords(2, 'public.person');

        $isValidUuid = Uuid::isValid($example['personId']);
        $entries = $isValidUuid ? $tester->grabEntriesFromDatabase('public.person', ['id' => $example['personId']]) : [];
        $otherEntries = $tester->grabEntriesFromDatabase('public.person', $isValidUuid ? ['id !=' => $example['personId']] : []);

        $response = $tester->sendRequest(
            new ServerRequest(
                parsedBody: ['_csrf' => 'test'],
                method: 'POST',
                uri: '/person-admin/' . $example['personId'] . '/delete',
            ),
        );

        assertSame($example['responseCode'], $response->getStatusCode());

        if ($example['responseCode'] === 303) {
            // No new records created
            $tester->seeNumRecords(1, 'public.person');

            // Deleted person
            foreach ($entries as $entry) {
                $tester->dontSeeInDatabase('public.person', $entry);
            }
        } else {
            $tester->seeNumRecords(2, 'public.person');
        }

        // All other records are present and unchanged
        foreach ($otherEntries as $entry) {
            $tester->seeInDatabase('public.person', $entry);
        }
    }

    public static function _deleteData(): array
    {
        return [
            [
                'testDescription' => 'existing',
                'personId' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                'responseCode' => 303,
            ],
            [
                'testDescription' => 'not found',
                'personId' => '00000000-0000-0000-0000-000000000000',
                'responseCode' => 404,
            ],
            [
                'testDescription' => 'new, not UUID, not found',
                'personId' => 'new',
                'responseCode' => 404,
            ],
        ];
    }
}

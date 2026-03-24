<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use HttpSoft\Message\ServerRequest;
use Ramsey\Uuid\Uuid;

use function PHPUnit\Framework\assertSame;

final class MarkChorePageCest
{
    #[DataProvider('_markData')]
    public function mark(FunctionalTester $tester, Example $example): void
    {
        $tester->expect('3 records to exist');
        $tester->seeNumRecords(3, 'public.chore');

        $isValidUuid = Uuid::isValid($example['choreId']);
        // grabEntryFromDatabase throws an exception if no records are found, so use grabEntriesFromDatabase instead
        $entries = $isValidUuid ? $tester->grabEntriesFromDatabase('public.chore', ['id' => $example['choreId']]) : [];
        $otherEntries = $tester->grabEntriesFromDatabase('public.chore', $isValidUuid ? ['id !=' => $example['choreId']] : []);

        $formData = [];

        if ($example->offsetExists('done')) {
            $formData['done'] = (int) $example['done'];
        }

        $response = $tester->sendRequest(
            new ServerRequest(
                parsedBody: [
                    '_csrf' => 'test',
                    'Form' => $formData
                ],
                method: 'POST',
                uri: '/chore-admin/' . $example['choreId'] . '/mark',
            ),
        );

        assertSame($example['responseCode'], $response->getStatusCode());

        $tester->expect('the same number of records');
        $tester->seeNumRecords(3, 'public.chore');

        $tester->expect('the record to have been edited correctly');
        foreach ($entries as $entry) {
            $tester->seeInDatabase('public.chore', [
                'id' => $entry['id'],
                'person_id' => $entry['person_id'],
                'name' => $entry['name'],
                'done' => $formData['done'] ?? $entry['done'] ?? 0,
            ]);
        }

        $tester->expect('all other records to be present and unchanged');
        foreach ($otherEntries as $entry) {
            $tester->seeInDatabase('public.chore', $entry);
        }
    }

    public static function _markData(): array
    {
        return [
            [
                'testDescription' => 'existing, change to done',
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'done' => true,
            ],
            [
                'testDescription' => 'existing, same',
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'done' => false,
            ],
            [
                'testDescription' => 'existing, change to not done',
                'responseCode' => 200,
                'choreId' => '019cd5ce-3d83-712c-9447-51209658dd41',
                'done' => false,
            ],
            [
                'testDescription' => 'existing, empty',
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
            ],
            [
                'testDescription' => 'not found',
                'responseCode' => 404,
                'choreId' => '00000000-0000-0000-0000-000000000000',
                'done' => true,
            ],
            [
                'testDescription' => 'new, not UUID, not found',
                'responseCode' => 404,
                'choreId' => 'new',
                'done' => true,
            ],
        ];
    }
}

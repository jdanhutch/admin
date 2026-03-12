<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use HttpSoft\Message\ServerRequest;
use Ramsey\Uuid\Uuid;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class EditChorePageCest
{
    #[DataProvider('_loadData')]
    public function load(FunctionalTester $tester, Example $example): void
    {
        $entries = $tester->grabEntriesFromDatabase('public.chore');

        $response = $tester->sendRequest(
            new ServerRequest(
                method: 'GET',
                uri: '/chore-admin/' . $example['choreId'] . '/edit',
            ),
        );
        $contents = $response->getBody()->getContents();

        assertSame($example['responseCode'], $response->getStatusCode());

        foreach ($example['strings'] as $string) {
            assertStringContainsString($string, $contents);
        }

        // All records are present and unchanged
        foreach ($entries as $entry) {
            $tester->seeInDatabase('public.chore', $entry);
        }
    }

    public static function _loadData(): array
    {
        return [
            [
                'testDescription' => 'new',
                'choreId' => 'new',
                'responseCode' => 200,
                'strings' => ['Save']
            ],
            [
                'testDescription' => 'existing',
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'responseCode' => 200,
                'strings' => ['Save', 'Do the laundry']
            ],
            [
                'testDescription' => 'not found',
                'choreId' => '00000000-0000-0000-0000-000000000000',
                'responseCode' => 404,
                'strings' => []
            ],
            [
                'testDescription' => 'not new, not UUID, not found',
                'choreId' => 'old',
                'responseCode' => 404,
                'strings' => []
            ],
        ];
    }

    #[DataProvider('_editData')]
    public function edit(FunctionalTester $tester, Example $example): void
    {
        $expectedPerson = $example['formData']['person'] ?? null;
        $expectedName = $example['formData']['name'] ?? null;

        if ($example['choreId'] !== 'new') {
            $expectedPerson ??= $tester->grabfromDatabase('public.chore', 'person_id', ['id' => $example['choreId']]);
            $expectedName ??= $tester->grabfromDatabase('public.chore', 'name', ['id' => $example['choreId']]);
        } elseif ($expectedName) {
            $tester->dontSeeInDatabase('public.chore', ['name' => $expectedName]);
        }

        $tester->seeNumRecords(3, 'public.chore');

        $isValidUuid = Uuid::isValid($example['choreId']);
        $entries = $isValidUuid ? $tester->grabEntriesFromDatabase('public.chore', ['id' => $example['choreId']]) : [];
        $otherEntries = $tester->grabEntriesFromDatabase('public.chore', $isValidUuid ? ['id !=' => $example['choreId']] : []);

        $response = $tester->sendRequest(
            new ServerRequest(
                parsedBody: [
                    '_csrf' => 'test',
                    'Form' => $example['formData']
                ],
                method: 'POST',
                uri: '/chore-admin/' . $example['choreId'] . '/edit',
            ),
        );
        $contents = $response->getBody()->getContents();

        assertSame($example['responseCode'], $response->getStatusCode());
        $tester->seeNumRecords($example['expected']['numResults'], 'public.chore');

        if (!empty($example['errorMessages']) || $example['responseCode'] === 404) {
            $tester->dontSeeInDatabase('public.chore', ['name' => $expectedName]);

            foreach ($example['errorMessages'] as $errorMessage) {
                assertStringContainsString($errorMessage, $contents);
            }

            // Chore unchanged
            foreach ($entries as $entry) {
                $tester->seeInDatabase('public.chore', $entry);
            }
        } else {
            $choreId = $example['choreId'];

            if (!$choreId) {
                $location = $response->getHeaderLine('Location');
                $urlPath = parse_url(filter_var($location, FILTER_SANITIZE_URL), PHP_URL_PATH);
                assertSame(1, preg_match('#^/chore-admin/(.+)$#', $urlPath, $matches));
                $choreId = $matches[1];
            }

            // Created or edited chore correctly
            foreach ($entries as $entry) {
                $tester->seeInDatabase('public.chore', [
                    'id' => $choreId,
                    'person_id' => $expectedPerson,
                    'name' => $expectedName,
                    'done' => $entry['done'],
                ]);
            }
        }

        // All other records are present and unchanged
        foreach ($otherEntries as $entry) {
            $tester->seeInDatabase('public.chore', $entry);
        }
    }

    public static function _editData(): array
    {
        return [
            [
                'testDescription' => 'new, successful',
                'errorMessages' => [],
                'responseCode' => 303,
                'choreId' => 'new',
                'formData' => [
                    'person' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                    'name' => 'New chore',
                ],
                'expected' => [
                    'numResults' => 4,
                    'choreId' => '',
                ],
            ],
            [
                'testDescription' => 'new, invalid person',
                'errorMessages' => [
                    'The value of Person is not a valid UUID.',
                ],
                'responseCode' => 200,
                'choreId' => 'new',
                'formData' => [
                    'person' => '',
                    'name' => 'New chore',
                ],
                'expected' => [
                    'numResults' => 3,
                ],
            ],
            [
                'testDescription' => 'new, invalid name',
                'errorMessages' => [
                    'Name must contain at most 64 characters.',
                ],
                'responseCode' => 200,
                'choreId' => 'new',
                'formData' => [
                    'person' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                    'name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
                'expected' => [
                    'numResults' => 3,
                ],
            ],
            [
                'testDescription' => 'new, empty',
                'errorMessages' => [
                    'The value of Person is not a valid UUID.',
                    'Name must contain at least 1 character.',
                ],
                'responseCode' => 200,
                'choreId' => 'new',
                'formData' => [],
                'expected' => [
                    'numResults' => 3,
                ],
            ],
            [
                'testDescription' => 'existing, successful',
                'errorMessages' => [],
                'responseCode' => 303,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [
                    'person' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
                    'name' => 'Edited chore',
                ],
                'expected' => [
                    'numResults' => 3,
                    'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                ],
            ],
            [
                'testDescription' => 'existing, invalid person',
                'errorMessages' => [
                    'The value of Person is not a valid UUID.',
                ],
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [
                    'person' => '',
                    'name' => 'Edited chore',
                ],
                'expected' => [
                    'numResults' => 3,
                ],
            ],
            [
                'testDescription' => 'existing, invalid name',
                'errorMessages' => [
                    'Name must contain at most 64 characters.',
                ],
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [
                    'person' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
                    'name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
                'expected' => [
                    'numResults' => 3,
                ],
            ],
            [
                'testDescription' => 'existing, same',
                'errorMessages' => [],
                'responseCode' => 303,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [
                    'person' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                    'name' => 'Do the laundry',
                ],
                'expected' => [
                    'numResults' => 3,
                    'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                ],
            ],
            [
                'testDescription' => 'existing, empty',
                'errorMessages' => [],
                'responseCode' => 303,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [],
                'expected' => [
                    'numResults' => 3,
                    'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                ],
            ],
            [
                'testDescription' => 'not found',
                'errorMessages' => [],
                'responseCode' => 404,
                'choreId' => '00000000-0000-0000-0000-000000000000',
                'formData' => [
                    'person' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
                    'name' => 'Edited chore',
                ],
                'expected' => [
                    'numResults' => 3,
                ],
            ],
            [
                'testDescription' => 'not new, not UUID, not found',
                'errorMessages' => [],
                'responseCode' => 404,
                'choreId' => 'old',
                'formData' => [
                    'person' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
                    'name' => 'Edited chore',
                ],
                'expected' => [
                    'numResults' => 3,
                ],
            ],
        ];
    }
}

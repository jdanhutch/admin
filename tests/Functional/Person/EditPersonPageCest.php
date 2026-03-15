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

final class EditPersonPageCest
{
    #[DataProvider('_loadData')]
    public function load(FunctionalTester $tester, Example $example): void
    {
        $entries = $tester->grabEntriesFromDatabase('public.person');

        $response = $tester->sendRequest(
            new ServerRequest(
                method: 'GET',
                uri: '/person-admin/' . $example['personId'] . '/edit',
            ),
        );
        $contents = $response->getBody()->getContents();

        assertSame($example['responseCode'], $response->getStatusCode());

        foreach ($example['strings'] as $string) {
            assertStringContainsString($string, $contents);
        }

        // All records are present and unchanged
        foreach ($entries as $entry) {
            $tester->seeInDatabase('public.person', $entry);
        }
    }

    public static function _loadData(): array
    {
        return [
            [
                'testDescription' => 'new',
                'personId' => 'new',
                'responseCode' => 200,
                'strings' => ['Save']
            ],
            [
                'testDescription' => 'existing',
                'personId' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                'responseCode' => 200,
                'strings' => ['Save', 'John Doe']
            ],
            [
                'testDescription' => 'not found',
                'personId' => '00000000-0000-0000-0000-000000000000',
                'responseCode' => 404,
                'strings' => []
            ],
            [
                'testDescription' => 'not new, not UUID, not found',
                'personId' => 'old',
                'responseCode' => 404,
                'strings' => []
            ],
        ];
    }

    #[DataProvider('_editData')]
    public function edit(FunctionalTester $tester, Example $example): void
    {
        $name = $example['formData']['name'] ?? null;

        if ($example['personId'] !== 'new') {
            $name ??= $tester->grabfromDatabase('public.person', 'name', ['id' => $example['personId']]);
        } elseif ($name) {
            $tester->dontSeeInDatabase('public.person', ['name' => $name]);
        }

        $tester->seeNumRecords(2, 'public.person');

        $isValidUuid = Uuid::isValid($example['personId']);
        $entries = $isValidUuid ? $tester->grabEntriesFromDatabase('public.person', ['id' => $example['personId']]) : [];
        $otherEntries = $tester->grabEntriesFromDatabase('public.person', $isValidUuid ? ['id !=' => $example['personId']] : []);

        $isNew = $example['personId'] === 'new';

        $response = $tester->sendRequest(
            new ServerRequest(
                parsedBody: [
                    '_csrf' => 'test',
                    'Form' => $example['formData']
                ],
                method: 'POST',
                uri: '/person-admin/' . $example['personId'] . '/edit',
            ),
        );
        $contents = $response->getBody()->getContents();

        assertSame($example['responseCode'], $response->getStatusCode());

        if (!empty($example['errorMessages']) || $example['responseCode'] === 404) {
            $tester->seeNumRecords(2, 'public.person');
            $tester->dontSeeInDatabase('public.person', ['name' => $name]);

            foreach ($example['errorMessages'] as $errorMessage) {
                assertStringContainsString($errorMessage, $contents);
            }

            // Person unchanged
            foreach ($entries as $entry) {
                $tester->seeInDatabase('public.person', $entry);
            }
        } else {
            $location = $response->getHeaderLine('Location');
            $urlPath = parse_url(filter_var($location, FILTER_SANITIZE_URL), PHP_URL_PATH);
            assertSame(1, preg_match('#^/person-admin/(.+)$#', $urlPath, $matches));
            $personId = $isNew ? $matches[1] : $example['personId'];

            // Created or edited person
            $tester->seeNumRecords($isNew ? 3 : 2, 'public.person');

            // Created or edited person correctly
            foreach ($entries as $entry) {
                $tester->seeInDatabase('public.person', [
                    'id' => $personId,
                    'name' => $name,
                ]);
            }
        }

        // All other records are present and unchanged
        foreach ($otherEntries as $entry) {
            $tester->seeInDatabase('public.person', $entry);
        }
    }

    public static function _editData(): array
    {
        return [
            [
                'testDescription' => 'new, successful',
                'errorMessages' => [],
                'responseCode' => 303,
                'personId' => 'new',
                'formData' => [
                    'name' => 'New person',
                ],
            ],
            [
                'testDescription' => 'new, invalid name',
                'errorMessages' => [
                    'Name must contain at most 64 characters.',
                ],
                'responseCode' => 200,
                'personId' => 'new',
                'formData' => [
                    'name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
            ],
            [
                'testDescription' => 'new, empty',
                'errorMessages' => [
                    'Name must contain at least 1 character.',
                ],
                'responseCode' => 200,
                'personId' => 'new',
                'formData' => [],
            ],
            [
                'testDescription' => 'existing, successful',
                'errorMessages' => [],
                'responseCode' => 303,
                'personId' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                'formData' => [
                    'name' => 'Edited person',
                ],
            ],
            [
                'testDescription' => 'existing, invalid name',
                'errorMessages' => [
                    'Name must contain at most 64 characters.',
                ],
                'responseCode' => 200,
                'personId' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                'formData' => [
                    'name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
            ],
            [
                'testDescription' => 'existing, same',
                'errorMessages' => [],
                'responseCode' => 303,
                'personId' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                'formData' => [
                    'name' => 'John Doe',
                ],
            ],
            [
                'testDescription' => 'existing, empty',
                'errorMessages' => [],
                'responseCode' => 303,
                'personId' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                'formData' => [],
            ],
            [
                'testDescription' => 'not found',
                'errorMessages' => [],
                'responseCode' => 404,
                'personId' => '00000000-0000-0000-0000-000000000000',
                'formData' => [
                    'name' => 'Edited person',
                ],
            ],
            [
                'testDescription' => 'not new, not UUID, not found',
                'errorMessages' => [],
                'responseCode' => 404,
                'personId' => 'old',
                'formData' => [
                    'name' => 'Edited person',
                ],
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class EditChorePageCest
{
    #[DataProvider('loadData')]
    public function load(FunctionalTester $tester, Example $example): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(
                method: 'GET',
                uri: '/chore-admin/' . $example['choreId'] . '/edit'
            ),
        );
        $contents = $response->getBody()->getContents();

        assertSame($example['responseCode'], $response->getStatusCode());

        foreach ($example['strings'] as $string) {
            assertStringContainsString($string, $contents);
        }
    }

    public static function loadData(): array
    {
        return [
            'load new' => [
                'choreId' => 'new',
                'responseCode' => 200,
                'strings' => ['Save']
            ],
            'load existing' => [
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'responseCode' => 200,
                'strings' => ['Save', 'Do the laundry']
            ],
            'not found' => [
                'choreId' => '00000000-0000-0000-0000-000000000000',
                'responseCode' => 404,
                'strings' => []
            ],
        ];
    }

    #[DataProvider('editData')]
    public function edit(FunctionalTester $tester, Example $example): void
    {
        $tester->dontSeeInDatabase('public.chore', ['name' => $example['formData']['name']]);

        // Check same person's chore before request
        $tester->seeInDatabase('public.chore', [
            'id' => '019cd5ce-0b7c-7373-8514-256dad0fe4da',
            'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
            'name' => 'Wash dishes',
            'done' => false,
        ]);

        // Check other person's chore before request
        $tester->seeInDatabase('public.chore', [
            'id' => '019cd5ce-3d83-712c-9447-51209658dd41',
            'person_id' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
            'name' => 'Clean bathroom',
            'done' => true,
        ]);

        $isNew = $example['choreId'] === 'new';
        $isDone = $isNew ? false : ($tester->grabFromDatabase('public.chore', 'done', ['id' => $example['choreId']]) === true);
        $numRecords = $tester->grabNumRecords('public.chore');

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

        assertSame($example['responseCode'], $response->getStatusCode());

        if ($example['errorMessage'] || $example['responseCode'] === 404) {
            $tester->seeNumRecords($numRecords, 'public.chore');
            $tester->dontSeeInDatabase('public.chore', ['name' => $example['formData']['name']]);
        } else {
            $location = $response->getHeaderLine('Location');
            $urlPath = parse_url(filter_var($location, FILTER_SANITIZE_URL), PHP_URL_PATH);
            assertSame(1, preg_match('#^/chore-admin/(.+)$#', $urlPath, $matches));
            $choreId = $isNew ? $matches[1] : $example['choreId'];

            // Created chore
            $tester->seeNumRecords($isNew ? $numRecords + 1 : $numRecords, 'public.chore');

            // Created chore correctly
            $tester->seeInDatabase('public.chore', [
                'id' => $choreId,
                'person_id' => $example['formData']['person'],
                'name' => $example['formData']['name'],
                'done' => $isDone,
            ]);
        }

        // Did not edit wrong chore
        $tester->seeInDatabase('public.chore', [
            'id' => '019cd5ce-0b7c-7373-8514-256dad0fe4da',
            'person_id' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
            'name' => 'Wash dishes',
            'done' => false,
        ]);

        // Did not edit other person's chore
        $tester->seeInDatabase('public.chore', [
            'id' => '019cd5ce-3d83-712c-9447-51209658dd41',
            'person_id' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
            'name' => 'Clean bathroom',
            'done' => true,
        ]);
    }

    public static function editData(): array
    {
        return [
            'new chore, successful' => [
                'errorMessage' => '',
                'responseCode' => 303,
                'choreId' => 'new',
                'formData' => [
                    'person' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                    'name' => 'New chore',
                ],
            ],
            'new chore, invalid person' => [
                'errorMessage' => 'The value of Person is not a valid UUID.',
                'responseCode' => 200,
                'choreId' => 'new',
                'formData' => [
                    'person' => '',
                    'name' => 'New chore',
                ],
            ],
            'new chore, invalid name' => [
                'errorMessage' => 'Name must contain at most 64 characters.',
                'responseCode' => 200,
                'choreId' => 'new',
                'formData' => [
                    'person' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                    'name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
            ],
            'existing chore, successful' => [
                'errorMessage' => '',
                'responseCode' => 303,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [
                    'person' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
                    'name' => 'Edited chore',
                ],
            ],
            'existing chore, invalid person' => [
                'errorMessage' => 'The value of Person is not a valid UUID.',
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [
                    'person' => '',
                    'name' => 'Edited chore',
                ],
            ],
            'existing chore, invalid name' => [
                'errorMessage' => 'Name must contain at most 64 characters.',
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'formData' => [
                    'person' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
                    'name' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa',
                ],
            ],
            'not found' => [
                'errorMessage' => '',
                'responseCode' => 404,
                'choreId' => '00000000-0000-0000-0000-000000000000',
                'formData' => [
                    'person' => '019cd5cd-92ae-739c-82c9-ef18b268f774',
                    'name' => 'Edited chore',
                ],
            ],
        ];
    }
}

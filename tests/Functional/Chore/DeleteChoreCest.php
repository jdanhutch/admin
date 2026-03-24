<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use HttpSoft\Message\ServerRequest;
use Ramsey\Uuid\Uuid;

use function PHPUnit\Framework\assertSame;

final class DeleteChoreCest
{
    #[DataProvider('_deleteData')]
    public function delete(FunctionalTester $tester, Example $example): void
    {
        $tester->expect('3 records to exist');
        $tester->seeNumRecords(3, 'public.chore');

        $isValidUuid = Uuid::isValid($example['choreId']);
        $entries = $isValidUuid ? $tester->grabEntriesFromDatabase('public.chore', ['id' => $example['choreId']]) : [];
        $otherEntries = $tester->grabEntriesFromDatabase('public.chore', $isValidUuid ? ['id !=' => $example['choreId']] : []);

        $response = $tester->sendRequest(
            new ServerRequest(
                parsedBody: ['_csrf' => 'test'],
                method: 'POST',
                uri: '/chore-admin/' . $example['choreId'] . '/delete',
            ),
        );

        assertSame($example['responseCode'], $response->getStatusCode());

        if ($example['responseCode'] === 303) {
            $tester->expect('one less record');
            $tester->seeNumRecords(2, 'public.chore');

            $tester->expect('the record to be deleted');
            foreach ($entries as $entry) {
                $tester->dontSeeInDatabase('public.chore', $entry);
            }
        } else {
            $tester->expect('the same number of records');
            $tester->seeNumRecords(3, 'public.chore');
        }

        $tester->expect('all other records to be present and unchanged');
        foreach ($otherEntries as $entry) {
            $tester->seeInDatabase('public.chore', $entry);
        }
    }

    public static function _deleteData(): array
    {
        return [
            [
                'testDescription' => 'existing',
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'responseCode' => 303,
            ],
            [
                'testDescription' => 'not found',
                'choreId' => '00000000-0000-0000-0000-000000000000',
                'responseCode' => 404,
            ],
            [
                'testDescription' => 'new, not UUID, not found',
                'choreId' => 'new',
                'responseCode' => 404,
            ],
        ];
    }
}

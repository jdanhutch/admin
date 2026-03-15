<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class ViewChorePageCest
{
    #[DataProvider('_viewData')]
    public function view(FunctionalTester $tester, Example $example): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(uri: '/chore-admin/' . $example['choreId']),
        );
        $contents = $response->getBody()->getContents();

        assertSame($example['responseCode'], $response->getStatusCode());

        foreach ($example['strings'] as $string) {
            assertStringContainsString($string, $contents);
        }
    }

    public static function _viewData(): array
    {
        return [
            [
                'testDescription' => 'existing',
                'responseCode' => 200,
                'choreId' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587',
                'strings' => ['Do the laundry', 'Edit']
            ],
            [
                'testDescription' => 'not found',
                'responseCode' => 404,
                'choreId' => '00000000-0000-0000-0000-000000000000',
                'strings' => []
            ],
            [
                'testDescription' => 'new, not UUID, not found',
                'responseCode' => 404,
                'choreId' => 'new',
                'strings' => []
            ],
        ];
    }
}

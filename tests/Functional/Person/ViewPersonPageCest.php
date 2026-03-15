<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class ViewPersonPageCest
{
    #[DataProvider('_viewData')]
    public function view(FunctionalTester $tester, Example $example): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(uri: '/person-admin/' . $example['personId']),
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
                'personId' => '019cd5cd-8ba6-723d-8525-01672c6a37b6',
                'strings' => ['John Doe', 'Edit']
            ],
            [
                'testDescription' => 'not found',
                'responseCode' => 404,
                'personId' => '00000000-0000-0000-0000-000000000000',
                'strings' => []
            ],
            [
                'testDescription' => 'new, not UUID, not found',
                'responseCode' => 404,
                'personId' => 'new',
                'strings' => []
            ],
        ];
    }
}

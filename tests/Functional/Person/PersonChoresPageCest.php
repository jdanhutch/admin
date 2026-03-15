<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class PersonChoresPageCest
{
    public function base(FunctionalTester $tester): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(uri: '/person-admin/019cd5cd-8ba6-723d-8525-01672c6a37b6/chores'),
        );

        assertSame(200, $response->getStatusCode());
        assertStringContainsString(
            'Chores',
            $response->getBody()->getContents(),
        );
    }
}

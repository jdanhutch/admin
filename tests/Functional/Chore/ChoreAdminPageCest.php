<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class ChoreAdminPageCest
{
    public function base(FunctionalTester $tester): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(uri: '/chore-admin'),
        );

        assertSame(200, $response->getStatusCode());
        assertStringContainsString(
            'Chore Admin',
            $response->getBody()->getContents(),
        );
    }
}

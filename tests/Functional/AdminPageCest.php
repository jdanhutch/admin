<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class AdminPageCest
{
    public function base(FunctionalTester $tester): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(uri: '/admin'),
        );

        assertSame(200, $response->getStatusCode());
        assertStringContainsString(
            'Welcome to the admin page!',
            $response->getBody()->getContents(),
        );
    }
}

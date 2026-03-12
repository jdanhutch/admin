<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertStringContainsString;

final class ViewChorePageCest
{
    public function base(FunctionalTester $tester): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(uri: '/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587'),
        );
        $contents = $response->getBody()->getContents();

        assertSame(200, $response->getStatusCode());
        assertStringContainsString(
            'Do the laundry',
            $contents,
        );
        assertStringContainsString(
            'Edit',
            $contents,
        );
    }
}

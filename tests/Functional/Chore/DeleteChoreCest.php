<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Support\FunctionalTester;
use HttpSoft\Message\ServerRequest;

use function PHPUnit\Framework\assertSame;

final class DeleteChoreCest
{
    public function base(FunctionalTester $tester): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(
                parsedBody: ['_csrf' => 'test'],
                method: 'POST',
                uri: '/chore-admin/019cd5cd-d2d8-72a9-b4c3-41fef1019587/delete',
            ),
        );

        assertSame(303, $response->getStatusCode());

        // Deleted chore
        $tester->dontSeeInDatabase('public.chore', ['id' => '019cd5cd-d2d8-72a9-b4c3-41fef1019587']);

        // Did not delete wrong chore
        $tester->seeInDatabase('public.chore', ['id' => '019cd5ce-0b7c-7373-8514-256dad0fe4da']);
    }

    public function notFound(FunctionalTester $tester): void
    {
        $response = $tester->sendRequest(
            new ServerRequest(
                parsedBody: ['_csrf' => 'test'],
                method: 'POST',
                uri: '/chore-admin/00000000-0000-0000-0000-000000000000/delete'
            ),
        );

        assertSame(404, $response->getStatusCode());

        // Did not delete wrong chore
        $tester->seeInDatabase('public.chore', ['id' => '019cd5ce-0b7c-7373-8514-256dad0fe4da']);
    }
}

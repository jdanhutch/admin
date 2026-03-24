<?php

declare(strict_types=1);

use Yiisoft\Csrf\CsrfTokenInterface;
use Yiisoft\Csrf\StubCsrfToken;

return [
    CsrfTokenInterface::class => [
        'class' => StubCsrfToken::class,
        '__construct()' => ['test']
    ]
];

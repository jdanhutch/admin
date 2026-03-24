<?php

declare(strict_types=1);

namespace App\Web\Person\Admin\DataReader;

final readonly class Person
{
    public function __construct(
        public string $id,
        public string $name,
    ) {}
}
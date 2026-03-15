<?php

declare(strict_types=1);

namespace App\Web\Person\Chores\DataReader;

final readonly class Chore
{
    public function __construct(
        public string $id,
        public string $chore,
        public bool $done,
    ) {}
}

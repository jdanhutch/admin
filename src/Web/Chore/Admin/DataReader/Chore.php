<?php

declare(strict_types=1);

namespace App\Web\Chore\Admin\DataReader;

final readonly class Chore
{
    public function __construct(
        public string $id,
        public string $name,
        public string $personName,
    ) {}
}
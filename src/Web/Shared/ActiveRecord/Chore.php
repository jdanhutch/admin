<?php

declare(strict_types=1);

namespace App\Web\Shared\ActiveRecord;

use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;

final class Chore extends ActiveRecord
{
    use UuidActiveRecordTrait;

    public string $person_id;
    public string $name;
    public bool $done;

    public function relationQuery(string $name): ActiveQueryInterface
    {
        return match ($name) {
            'person' => $this->hasOne(Person::class, ['id' => 'person_id']),
            default => parent::relationQuery($name),
        };
    }

    public function getPerson(): Person|null
    {
        return $this->relation('person');
    }

    public function getId(): string
    {
        return $this->id;
    }
}

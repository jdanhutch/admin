<?php

declare(strict_types=1);

namespace App\Web\Shared\ActiveRecord;

use Yiisoft\ActiveRecord\ActiveQueryInterface;
use Yiisoft\ActiveRecord\ActiveRecord;

final class Person extends ActiveRecord
{
    use UuidActiveRecordTrait;

    public string $name;

    public function relationQuery(string $name): ActiveQueryInterface
    {
        return match ($name) {
            'chores' => $this->hasMany(Chore::class, ['person_id' => 'id']),
            default => parent::relationQuery($name),
        };
    }

    /**
     * @return Chore[]
     */
    public function getChores(): array
    {
        return $this->relation('chores');
    }
}

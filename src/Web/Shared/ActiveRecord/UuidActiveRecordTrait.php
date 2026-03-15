<?php

declare(strict_types=1);

namespace App\Web\Shared\ActiveRecord;

use Ramsey\Uuid\Uuid;
use Yiisoft\ActiveRecord\ActiveRecordInterface;
use Yiisoft\ActiveRecord\Trait\PrivatePropertiesTrait;
use Yiisoft\ActiveRecord\Trait\RepositoryTrait;

trait UuidActiveRecordTrait
{
    use PrivatePropertiesTrait;
    use RepositoryTrait;

    private string $id;

    public function generateId()
    {
        $this->id = Uuid::uuid7()->toString();
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Invalid UUIDs would cause SQL errors, so, for invalid UUIDs, this returns null without
     * searching the database.
     */
    public static function findByUuidPk(string $value): ActiveRecordInterface|null
    {
        return Uuid::isValid($value) ? static::findByPk($value) : null;
    }
}

<?php

declare(strict_types=1);

namespace App\Web\Person\Admin\DataReader;

use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;

final class PersonDataReader extends QueryDataReader
{
    public function __construct(
        ConnectionInterface $db,
    )
    {
        parent::__construct(
            $db->createQuery()
                ->select(['id', 'name'])
                ->from('person')
                ->resultCallback(
                    static fn (array $rows): array => array_map(
                        static fn (array $row): Person => new Person($row['id'], $row['name']),
                        $rows
                    )
                )
        );
    }
}
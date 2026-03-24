<?php

declare(strict_types=1);

namespace App\Web\Person\Chores\DataReader;

use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;

final class ChoreDataReader extends QueryDataReader
{
    public function __construct(
        string $personId,
        ConnectionInterface $db,
    )
    {
        parent::__construct(
            $db->createQuery()
                ->select(['id', 'name', 'done'])
                ->from('chore')
                ->where(['person_id' => $personId])
                ->orderBy(['name' => SORT_ASC])
                ->resultCallback(
                    static fn (array $rows): array => array_map(
                        static fn (array $row): Chore => new Chore($row['id'], $row['name'], $row['done']),
                        $rows
                    )
                )
        );
    }
}
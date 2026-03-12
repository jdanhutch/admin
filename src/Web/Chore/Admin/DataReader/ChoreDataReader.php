<?php

declare(strict_types=1);

namespace App\Web\Chore\Admin\DataReader;

use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Db\Connection\ConnectionInterface;

final class ChoreDataReader extends QueryDataReader
{
    public function __construct(
        ConnectionInterface $db,
    )
    {
        parent::__construct(
            $db->createQuery()
                ->select(['chore.id', 'chore.name', 'person.name AS person_name'])
                ->from('chore')
                ->innerJoin('person', 'person.id = chore.person_id')
                ->orderBy(['person.name' => SORT_ASC, 'name' => SORT_ASC])
                ->resultCallback(
                    static fn (array $rows): array => array_map(
                        static fn (array $row): Chore => new Chore($row['id'], $row['name'], $row['person_name']),
                        $rows
                    )
                )
        );
    }
}

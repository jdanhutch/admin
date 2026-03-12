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
                ->select(['id', 'name'])
                ->from('chore')
                ->resultCallback(
                    static fn (array $rows): array => array_map(
                        static fn (array $row): Chore => new Chore($row['id'], $row['name']),
                        $rows
                    )
                )
        );
    }
}
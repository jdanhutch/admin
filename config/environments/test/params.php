<?php

declare(strict_types=1);

use Yiisoft\Db\Pgsql\Dsn;

return [
    'yiisoft/db-pgsql' => [
        'dsn' => new Dsn('pgsql', 'localhost', 'yii3_admin_test', '5432'),
        'username' => 'postgres',
        'password' => 'password',
    ]
];

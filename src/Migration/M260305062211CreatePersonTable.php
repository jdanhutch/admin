<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;

/**
 * Handles the creation of table `person`.
 */
final class M260305062211CreatePersonTable implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $columnBuilder = $b->columnBuilder();

        $b->createTable('person', [
            'id' => $columnBuilder::uuidPrimaryKey(false)->primaryKey(),
            'name' => $columnBuilder::string(64)->notNull(),
        ]);

        $b->addCommentOnTable('person', 'Records for people who have chores to do');
    }

    public function down(MigrationBuilder $b): void
    {
        $b->dropTable('person');
    }
}

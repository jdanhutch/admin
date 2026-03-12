<?php

declare(strict_types=1);

namespace App\Migration;

use Yiisoft\Db\Migration\MigrationBuilder;
use Yiisoft\Db\Migration\RevertibleMigrationInterface;
use Yiisoft\Db\Migration\TransactionalMigrationInterface;

/**
 * Handles the creation of table `chore`.
 * Has foreign keys to the tables:
 *
 * - `{{%person}}`
 */
final class M260305062213CreateChoreTable implements RevertibleMigrationInterface, TransactionalMigrationInterface
{
    public function up(MigrationBuilder $b): void
    {
        $columnBuilder = $b->columnBuilder();

        $b->createTable('chore', [
            'id' => $columnBuilder::uuidPrimaryKey(false)->primaryKey(),
            'person_id' => $columnBuilder::uuid()->notNull(),
            'name' => $columnBuilder::string(64)->notNull(),
            'done' => $columnBuilder::boolean()->defaultValue(false)->notNull(),
        ]);

        // creates index for column `person_id`
        $b->createIndex(
            'chore',
            'idx-chore-person_id',
            'person_id'
        );

        // add foreign key for table `{{%person}}`
        $b->addForeignKey(
            'chore',
            'fk-chore-person_id',
            'person_id',
            '{{%person}}',
            'id',
            'CASCADE'
        );

        $b->addCommentOnTable('chore', 'Records of the chores that people need to do');
    }

    public function down(MigrationBuilder $b): void
    {
        // drops foreign key for table `{{%person}}`
        $b->dropForeignKey(
            'chore',
            'fk-chore-person_id'
        );

        // drops index for column `person_id`
        $b->dropIndex(
            'chore',
            'idx-chore-person_id'
        );

        $b->dropTable('chore');
    }
}

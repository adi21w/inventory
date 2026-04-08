<?php

use yii\db\Migration;

class m260408_021246_create_table_x_auth_assignment extends Migration
{
    public function safeUp()
    {
        // optional: disable FK check (mirip SQL lo)
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
        ], $tableOptions);

        // primary key composite
        $this->addPrimaryKey(
            'pk-auth_assignment',
            '{{%auth_assignment}}',
            ['item_name', 'user_id']
        );

        // index
        $this->createIndex(
            'idx-auth_assignment-user_id',
            '{{%auth_assignment}}',
            'user_id'
        );

        // foreign key ke auth_item
        $this->addForeignKey(
            'fk-auth_assignment-item_name',
            '{{%auth_assignment}}',
            'item_name',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );

        // insert data
        $this->batchInsert(
            '{{%auth_assignment}}',
            ['item_name', 'user_id', 'created_at'],
            [
                ['Admin', '2', 1775467477],
                ['Super User', '1', 1775449468],
                ['Super User', '3', 1775489912],
            ]
        );

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-auth_assignment-item_name', '{{%auth_assignment}}');
        $this->dropIndex('idx-auth_assignment-user_id', '{{%auth_assignment}}');
        $this->dropPrimaryKey('pk-auth_assignment', '{{%auth_assignment}}');
        $this->dropTable('{{%auth_assignment}}');
    }
}

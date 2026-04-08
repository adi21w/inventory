<?php

use yii\db\Migration;

class m260408_021218_create_table_x_auth_item_child extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_item_child}}', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
        ], $tableOptions);

        // primary key composite
        $this->addPrimaryKey(
            'pk-auth_item_child',
            '{{%auth_item_child}}',
            ['parent', 'child']
        );

        // index
        $this->createIndex(
            'idx-auth_item_child-child',
            '{{%auth_item_child}}',
            'child'
        );

        // FK ke auth_item (parent)
        $this->addForeignKey(
            'fk-auth_item_child-parent',
            '{{%auth_item_child}}',
            'parent',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );

        // FK ke auth_item (child)
        $this->addForeignKey(
            'fk-auth_item_child-child',
            '{{%auth_item_child}}',
            'child',
            '{{%auth_item}}',
            'name',
            'CASCADE',
            'CASCADE'
        );

        // insert data
        $this->batchInsert(
            '{{%auth_item_child}}',
            ['parent', 'child'],
            [
                ['Admin', '/#'],
                ['Super User', '/#'],
                ['Super User', '/adjustment-minus/*'],
                ['Super User', '/adjustment-plus/*'],
                ['Super User', '/admin/*'],
                ['Super User', '/gii'],
                ['Super User', '/gridview/export/*'],
                ['Super User', '/history-price/*'],
                ['Super User', '/packs/*'],
                ['Super User', '/products/*'],
                ['Super User', '/rack/*'],
                ['Admin', '/site/*'],
                ['Super User', '/site/*'],
                ['Super User', '/stock/*'],
                ['Super User', '/transfer-in/*'],
                ['Super User', '/transfer-out/*'],
                ['Super User', '/trhist/*'],
                ['Super User', '/user/*'],
                ['Super User', '/warehouses/*'],
            ]
        );

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-auth_item_child-child', '{{%auth_item_child}}');
        $this->dropForeignKey('fk-auth_item_child-parent', '{{%auth_item_child}}');
        $this->dropIndex('idx-auth_item_child-child', '{{%auth_item_child}}');
        $this->dropPrimaryKey('pk-auth_item_child', '{{%auth_item_child}}');
        $this->dropTable('{{%auth_item_child}}');
    }
}

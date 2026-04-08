<?php

use yii\db\Migration;

class m260408_021100_create_table_x_auth_item extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_item}}', [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        // primary key
        $this->addPrimaryKey('pk-auth_item', '{{%auth_item}}', 'name');

        // index
        $this->createIndex('idx-auth_item-type', '{{%auth_item}}', 'type');
        $this->createIndex('idx-auth_item-rule_name', '{{%auth_item}}', 'rule_name');

        // foreign key ke auth_rule
        $this->addForeignKey(
            'fk-auth_item-rule_name',
            '{{%auth_item}}',
            'rule_name',
            '{{%auth_rule}}',
            'name',
            'SET NULL',
            'CASCADE'
        );

        // insert data
        $this->batchInsert(
            '{{%auth_item}}',
            ['name', 'type', 'description', 'rule_name', 'data', 'created_at', 'updated_at'],
            [
                ['/#', 2, null, null, null, 1775451695, 1775451695],
                ['/adjustment-minus/*', 2, null, null, null, 1775533466, 1775533466],
                ['/adjustment-minus/index', 2, null, null, null, 1775533472, 1775533472],
                ['/adjustment-plus/*', 2, null, null, null, 1775533454, 1775533454],
                ['/adjustment-plus/index', 2, null, null, null, 1775533459, 1775533459],
                ['/admin/*', 2, null, null, null, 1775448897, 1775448897],
                ['/admin/menu/index', 2, null, null, null, 1775452547, 1775452547],
                ['/gii', 2, null, null, null, 1775452561, 1775452561],
                ['/gridview/export/*', 2, null, null, null, 1775456458, 1775456458],
                ['/history-price/*', 2, null, null, null, 1775462421, 1775462421],
                ['/packs/*', 2, null, null, null, 1775454431, 1775454431],
                ['/packs/index', 2, null, null, null, 1775454436, 1775454436],
                ['/products/*', 2, null, null, null, 1775453194, 1775453194],
                ['/products/index', 2, null, null, null, 1775453198, 1775453198],
                ['/rack/*', 2, null, null, null, 1775525657, 1775525657],
                ['/rack/index', 2, null, null, null, 1775525662, 1775525662],
                ['/site/*', 2, null, null, null, 1775448904, 1775448904],
                ['/site/index', 2, null, null, null, 1775448906, 1775448906],
                ['/site/profile', 2, null, null, null, 1775448913, 1775448913],
                ['/stock/*', 2, null, null, null, 1775533481, 1775533481],
                ['/stock/index', 2, null, null, null, 1775533485, 1775533485],
                ['/transfer-in/*', 2, null, null, null, 1775533992, 1775533992],
                ['/transfer-in/index', 2, null, null, null, 1775533997, 1775533997],
                ['/transfer-out/*', 2, null, null, null, 1775534004, 1775534004],
                ['/transfer-out/index', 2, null, null, null, 1775534008, 1775534008],
                ['/trhist/*', 2, null, null, null, 1775561227, 1775561227],
                ['/user/*', 2, null, null, null, 1775466445, 1775466445],
                ['/user/index', 2, null, null, null, 1775466656, 1775466656],
                ['/warehouses/*', 2, null, null, null, 1775453218, 1775453218],
                ['/warehouses/index', 2, null, null, null, 1775453222, 1775453222],
                ['Admin', 1, null, null, null, 1775449452, 1775449452],
                ['Super User', 1, 'All Akses', null, null, 1775449439, 1775449439],
            ]
        );

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-auth_item-rule_name', '{{%auth_item}}');
        $this->dropIndex('idx-auth_item-rule_name', '{{%auth_item}}');
        $this->dropIndex('idx-auth_item-type', '{{%auth_item}}');
        $this->dropPrimaryKey('pk-auth_item', '{{%auth_item}}');
        $this->dropTable('{{%auth_item}}');
    }
}

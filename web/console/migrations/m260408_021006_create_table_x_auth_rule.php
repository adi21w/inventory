<?php

use yii\db\Migration;

class m260408_021006_create_table_x_auth_rule extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%auth_rule}}', [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        // primary key
        $this->addPrimaryKey('pk-auth_rule', '{{%auth_rule}}', 'name');

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropPrimaryKey('pk-auth_rule', '{{%auth_rule}}');
        $this->dropTable('{{%auth_rule}}');
    }
}

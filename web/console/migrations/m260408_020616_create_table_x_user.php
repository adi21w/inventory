<?php

use yii\db\Migration;

class m260408_020616_create_table_x_user extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            'name' => $this->string(100),
            'number_phone' => $this->string(13),
        ]);

        // Opsional: Jika ingin memasukkan data awal (Records)
        $this->batchInsert(
            '{{%user}}',
            ['username', 'password_hash', 'email', 'name', 'number_phone'],
            [
                ['X0001', '$2y$10$AGSwWGstCGeCiksnuiOMQ.6tGq7/Rf8ksKT5s8ZoGR.ak3kyvzT/e', 'widarjoadi@gmail.com', 'Adi Widarjo', '087882944250'],
                ['CP002', '$2y$10$0.YcLNgE/Lnsaer9tLpfVOoWqNmhnoFIZDmCaXO14BCqtz2H.WJEu', 'lily04@gmail.com', 'Lily Sarinem', '05754841214'],
                ['X0002', '$2y$10$Y2Wrxxia8wbjyS2oSZDVm.eu8Vk4tmEbfUDLQuf4GXnWbyxj0wXRG', 'system@rm.com', 'System', '875454114111'],
            ]
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}

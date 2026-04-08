<?php

use yii\db\Migration;

class m260408_022434_create_table_x_history_price extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%h_product_price}}', [
            'iId' => $this->primaryKey(),
            'iProdukId' => $this->integer(),
            'oldPrice' => $this->decimal(14, 2),
            'newPrice' => $this->decimal(14, 2),
            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
            'iUpdatedid' => $this->integer(),
        ], $tableOptions);

        // index
        $this->createIndex(
            'idx-h_product_price-product',
            '{{%h_product_price}}',
            'iProdukId'
        );

        // optional FK ke product (kalau tabel product ada)
        // uncomment kalau ada tabel product
        /*
        $this->addForeignKey(
            'fk-h_product_price-product',
            '{{%h_product_price}}',
            'iProdukId',
            '{{%product}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
        */

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        // kalau FK dipakai, drop dulu
        /*
        $this->dropForeignKey('fk-h_product_price-product', '{{%h_product_price}}');
        */

        $this->dropIndex('idx-h_product_price-product', '{{%h_product_price}}');
        $this->dropTable('{{%h_product_price}}');
    }
}

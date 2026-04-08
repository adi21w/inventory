<?php

use yii\db\Migration;

class m260408_022626_create_table_x_stock extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%stock}}', [
            'iId' => $this->primaryKey()->comment('id stock'),
            'iProdukId' => $this->integer()->notNull()->comment('id barang'),
            'iGudangId' => $this->integer()->notNull()->comment('id gudang'),
            'iQty' => $this->integer()->defaultValue(0)->comment('jumlah stock'),
            'tCreated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->comment('timestamp dibuat'),
            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP')
                ->comment('timestamp diupdate'),
            'iCreatedId' => $this->integer()->comment('id yang membuat'),
            'iUpdatedId' => $this->integer()->comment('id yang mengupdate'),
        ], $tableOptions);

        // index kombinasi product + gudang
        $this->createIndex(
            'idx-stock-product-gudang',
            '{{%stock}}',
            ['iProdukId', 'iGudangId']
        );

        // FK ke product
        $this->addForeignKey(
            'fk-stock-product',
            '{{%stock}}',
            'iProdukId',
            '{{%m_products}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        // FK ke warehouse
        $this->addForeignKey(
            'fk-stock-warehouse',
            '{{%stock}}',
            'iGudangId',
            '{{%m_warehouse}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-stock-warehouse', '{{%stock}}');
        $this->dropForeignKey('fk-stock-product', '{{%stock}}');
        $this->dropIndex('idx-stock-product-gudang', '{{%stock}}');
        $this->dropTable('{{%stock}}');
    }
}

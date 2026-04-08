<?php

use yii\db\Migration;

class m260408_022647_create_table_x_adj_dt extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%t_adjustment_detail}}', [
            'iId' => $this->primaryKey(),
            'vAdjNo' => $this->string(20)->notNull(),

            'iProdukId' => $this->integer()->notNull(),
            'iKemasanId' => $this->integer()->notNull(),

            'vBatch' => $this->string(50)->notNull(),
            'dExpired' => $this->date(),

            'iQty' => $this->integer()->notNull(),
            'iQty2' => $this->integer(),

            'dHarga' => $this->decimal(14, 2),
            'dHargasatuan' => $this->decimal(14, 2),
            'dTotal' => $this->decimal(14, 2),

            'iCreatedId' => $this->integer(),
            'iUpdatedId' => $this->integer(),

            'tCreated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP'),

            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // index
        $this->createIndex(
            'idx-adjustment-detail-no',
            '{{%t_adjustment_detail}}',
            'vAdjNo'
        );

        $this->createIndex(
            'idx-adjustment-detail-product',
            '{{%t_adjustment_detail}}',
            ['iProdukId', 'iKemasanId']
        );

        $this->createIndex(
            'idx-adjustment-detail-batch',
            '{{%t_adjustment_detail}}',
            ['vBatch', 'dExpired']
        );

        // FK ke header (pakai nomor dokumen)
        $this->addForeignKey(
            'fk-adjustment-detail-header',
            '{{%t_adjustment_detail}}',
            'vAdjNo',
            '{{%t_adjustment_header}}',
            'vAdjNo',
            'CASCADE',
            'CASCADE'
        );

        // FK ke product
        $this->addForeignKey(
            'fk-adjustment-detail-product',
            '{{%t_adjustment_detail}}',
            'iProdukId',
            '{{%m_products}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        // FK ke kemasan
        $this->addForeignKey(
            'fk-adjustment-detail-pack',
            '{{%t_adjustment_detail}}',
            'iKemasanId',
            '{{%m_packs}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-adjustment-detail-pack', '{{%t_adjustment_detail}}');
        $this->dropForeignKey('fk-adjustment-detail-product', '{{%t_adjustment_detail}}');
        $this->dropForeignKey('fk-adjustment-detail-header', '{{%t_adjustment_detail}}');

        $this->dropIndex('idx-adjustment-detail-batch', '{{%t_adjustment_detail}}');
        $this->dropIndex('idx-adjustment-detail-product', '{{%t_adjustment_detail}}');
        $this->dropIndex('idx-adjustment-detail-no', '{{%t_adjustment_detail}}');

        $this->dropTable('{{%t_adjustment_detail}}');
    }
}

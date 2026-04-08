<?php

use yii\db\Migration;

class m260408_022642_create_table_x_adj_hd extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%t_adjustment_header}}', [
            'iId' => $this->primaryKey(),
            'vAdjNo' => $this->string(20)->notNull(),
            'iGudangId' => $this->integer()->notNull(),

            'eKategori' => "ENUM(
                'STOCK OPNAME',
                'EXPIRED',
                'HILANG',
                'SALDO AWAL',
                'DITEMUKAN',
                'PEMBETULAN BATCH',
                'PEMUSNAHAN'
            ) NOT NULL",

            'eType' => "ENUM('MINUS','PLUS')",

            'dConfirm' => $this->date(),
            'eConfirm' => "ENUM('Ya','Tidak') NOT NULL DEFAULT 'Tidak'",

            'dTotal' => $this->decimal(14, 2),
            'tKeterangan' => $this->text(),

            'eDeleted' => "ENUM('Ya','Tidak') DEFAULT 'Tidak'",

            'iCreatedId' => $this->integer(),
            'iUpdatedId' => $this->integer(),

            'tCreated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP'),

            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // unique nomor adjustment
        $this->createIndex(
            'uq-adjustment-no',
            '{{%t_adjustment_header}}',
            'vAdjNo',
            true
        );

        // index filtering
        $this->createIndex(
            'idx-adjustment-filter',
            '{{%t_adjustment_header}}',
            ['eType', 'eKategori', 'dConfirm', 'eConfirm']
        );

        $this->createIndex(
            'idx-adjustment-gudang',
            '{{%t_adjustment_header}}',
            'iGudangId'
        );

        // FK ke warehouse
        $this->addForeignKey(
            'fk-adjustment-warehouse',
            '{{%t_adjustment_header}}',
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
        $this->dropForeignKey('fk-adjustment-warehouse', '{{%t_adjustment_header}}');

        $this->dropIndex('idx-adjustment-gudang', '{{%t_adjustment_header}}');
        $this->dropIndex('idx-adjustment-filter', '{{%t_adjustment_header}}');
        $this->dropIndex('uq-adjustment-no', '{{%t_adjustment_header}}');

        $this->dropTable('{{%t_adjustment_header}}');
    }
}

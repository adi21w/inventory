<?php

use yii\db\Migration;

class m260408_022609_create_table_x_warehouse extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%m_warehouse}}', [
            'iId' => $this->primaryKey(),
            'vNama' => $this->string(30)->notNull(),
            'iStatus' => $this->integer(),
            'iCreatedId' => $this->integer(),
            'iUpdatedId' => $this->integer(),
            'tCreated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP'),
            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
            'eDeleted' => "ENUM('Ya','Tidak') DEFAULT 'Tidak'",
            'eStock' => "ENUM('Ya','Tidak') DEFAULT 'Tidak'",
        ], $tableOptions);

        // index
        $this->createIndex(
            'idx-m_warehouse-gudang',
            '{{%m_warehouse}}',
            ['vNama', 'eDeleted', 'eStock']
        );

        $this->createIndex(
            'idx-m_warehouse-status',
            '{{%m_warehouse}}',
            'iStatus'
        );

        // insert data
        $this->batchInsert(
            '{{%m_warehouse}}',
            ['iId', 'vNama', 'iStatus', 'iCreatedId', 'iUpdatedId', 'tCreated', 'tUpdated', 'eDeleted', 'eStock'],
            [
                [1, 'GOODS', 1, 1, 1, '2026-04-06 17:11:06', '2026-04-06 17:11:06', 'Tidak', 'Ya'],
                [2, 'EXPIRED', 1, 1, 1, '2026-04-06 17:11:15', '2026-04-06 17:11:15', 'Tidak', 'Ya'],
                [3, 'EVENT', 0, 1, 1, '2026-04-06 17:11:22', '2026-04-07 10:38:18', 'Tidak', 'Tidak'],
                [4, 'GUDANG 404', 0, 1, 3, '2026-04-06 23:55:32', '2026-04-07 00:00:18', 'Ya', 'Tidak'],
            ]
        );

        $this->execute("ALTER TABLE {{%m_warehouse}} AUTO_INCREMENT = 5");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-m_warehouse-status', '{{%m_warehouse}}');
        $this->dropIndex('idx-m_warehouse-gudang', '{{%m_warehouse}}');
        $this->dropTable('{{%m_warehouse}}');
    }
}

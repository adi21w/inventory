<?php

use yii\db\Migration;

class m260408_022603_create_table_x_racks extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%m_rack}}', [
            'iId' => $this->primaryKey(),
            'vNama' => $this->string(25)->notNull()->comment('Nama Rack'),
            'eDeleted' => "ENUM('Ya','Tidak') DEFAULT 'Tidak' COMMENT 'Pasif Delete'",
            'tCreated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->comment('Timestamp pembuatan data'),
            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP')
                ->comment('Timestamp modifikasi data'),
            'iCreatedId' => $this->integer()->comment('User yang membuat data'),
            'iUpdatedId' => $this->integer()->comment('User yang modifikasi data'),
        ], $tableOptions);

        // index
        $this->createIndex(
            'idx-m_rack-nama',
            '{{%m_rack}}',
            ['vNama', 'eDeleted']
        );

        // insert data
        $this->batchInsert(
            '{{%m_rack}}',
            ['iId', 'vNama', 'eDeleted', 'tCreated', 'tUpdated', 'iCreatedId', 'iUpdatedId'],
            [
                [1, '1A', 'Tidak', '2026-04-07 08:40:56', '2026-04-07 08:40:56', 1, 1],
                [2, '1B-1', 'Tidak', '2026-04-07 08:48:09', '2026-04-07 08:48:09', 1, 3],
                [3, '1C', 'Tidak', '2026-04-07 08:45:22', '2026-04-07 08:45:22', 3, 3],
            ]
        );

        $this->execute("ALTER TABLE {{%m_rack}} AUTO_INCREMENT = 4");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-m_rack-nama', '{{%m_rack}}');
        $this->dropTable('{{%m_rack}}');
    }
}

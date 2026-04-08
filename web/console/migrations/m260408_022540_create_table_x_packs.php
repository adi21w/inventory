<?php

use yii\db\Migration;

class m260408_022540_create_table_x_packs extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%m_packs}}', [
            'iId' => $this->smallPrimaryKey(),
            'vNama' => $this->string(25)->notNull()->comment('Nama Kemasan'),
            'eDeleted' => "ENUM('Ya','Tidak') DEFAULT 'Tidak' COMMENT 'Pasif Delete'",
            'tCreated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP')
                ->comment('Timestamp pembuatan data'),
            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP')
                ->comment('Timestamp modifikasi data'),
            'iCreatedId' => $this->integer()->comment('User yang membuat data'),
            'iUpdatedId' => $this->integer()->comment('User yang memodifikasi data'),
        ], $tableOptions);

        // index nama + eDeleted
        $this->createIndex(
            'idx-m_packs-nama',
            '{{%m_packs}}',
            ['vNama', 'eDeleted']
        );

        // insert data
        $this->batchInsert(
            '{{%m_packs}}',
            ['iId', 'vNama', 'eDeleted', 'tCreated', 'tUpdated', 'iCreatedId', 'iUpdatedId'],
            [
                [1, 'BOX', 'Tidak', '2026-04-06 13:09:12', '2026-04-06 13:09:12', 1, 1],
                [2, 'BOTOL', 'Tidak', '2026-04-06 23:18:42', '2026-04-06 23:18:42', 1, 3],
                [3, 'PCS', 'Tidak', '2026-04-06 23:39:24', '2026-04-06 23:39:24', 1, 1],
                [4, 'POUCH', 'Tidak', '2026-04-06 23:48:19', '2026-04-06 23:48:19', 3, 3],
            ]
        );

        $this->execute("ALTER TABLE {{%m_packs}} AUTO_INCREMENT = 5");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-m_packs-nama', '{{%m_packs}}');
        $this->dropTable('{{%m_packs}}');
    }
}

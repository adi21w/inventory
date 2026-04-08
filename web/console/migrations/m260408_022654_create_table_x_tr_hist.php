<?php

use yii\db\Migration;

class m260408_022654_create_table_x_tr_hist extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tr_hist}}', [
            'iId' => $this->primaryKey(),

            'vTr_Number' => $this->string(30)->notNull(),
            'eTr_Type' => "ENUM('ADJ-IN','ADJ-OUT','TRS-IN','TRS-OUT') NOT NULL",

            'iTr_ProdukId' => $this->integer()->notNull(),
            'vTr_Batch' => $this->string(50)->notNull(),
            'iTr_GudangId' => $this->integer()->notNull(),

            'iTr_Qtybef' => $this->integer()->defaultValue(0),
            'iTr_Qty' => $this->integer()->defaultValue(0),
            'iTr_Qtyend' => $this->integer()->defaultValue(0),

            'iTr_Kemasan' => $this->integer(),

            'dTr_Date' => $this->date(),

            'dHarga' => $this->decimal(14, 2),
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
            'idx-trhist-filter',
            '{{%tr_hist}}',
            ['eTr_Type', 'vTr_Batch', 'dTr_Date']
        );

        $this->createIndex(
            'idx-trhist-product',
            '{{%tr_hist}}',
            ['iTr_ProdukId', 'iTr_GudangId', 'iTr_Kemasan']
        );

        $this->createIndex(
            'idx-trhist-number',
            '{{%tr_hist}}',
            'vTr_Number'
        );

        // FK (optional tapi bagus)
        $this->addForeignKey(
            'fk-trhist-product',
            '{{%tr_hist}}',
            'iTr_ProdukId',
            '{{%m_products}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-trhist-warehouse',
            '{{%tr_hist}}',
            'iTr_GudangId',
            '{{%m_warehouse}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        // =========================
        // TRIGGER (OPTIMIZED)
        // =========================

        // BEFORE INSERT
        $this->execute("
            CREATE TRIGGER trg_tr_hist_before_insert
            BEFORE INSERT ON {{%tr_hist}}
            FOR EACH ROW
            BEGIN
                DECLARE qty_before INT DEFAULT 0;

                SELECT IFNULL(SUM(iTr_Qty),0)
                INTO qty_before
                FROM {{%tr_hist}}
                WHERE iTr_ProdukId = NEW.iTr_ProdukId
                  AND iTr_GudangId = NEW.iTr_GudangId
                  AND vTr_Batch = NEW.vTr_Batch;

                SET NEW.iTr_Qtybef = qty_before;
                SET NEW.iTr_Qtyend = qty_before + NEW.iTr_Qty;
            END
        ");

        // BEFORE UPDATE
        $this->execute("
            CREATE TRIGGER trg_tr_hist_before_update
            BEFORE UPDATE ON {{%tr_hist}}
            FOR EACH ROW
            BEGIN
                DECLARE qty_before INT DEFAULT 0;

                SELECT IFNULL(SUM(iTr_Qty),0)
                INTO qty_before
                FROM {{%tr_hist}}
                WHERE iTr_ProdukId = NEW.iTr_ProdukId
                  AND iTr_GudangId = NEW.iTr_GudangId
                  AND vTr_Batch = NEW.vTr_Batch
                  AND iId <> NEW.iId;

                SET NEW.iTr_Qtybef = qty_before;
                SET NEW.iTr_Qtyend = qty_before + NEW.iTr_Qty;
            END
        ");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->execute("DROP TRIGGER IF EXISTS trg_tr_hist_before_insert");
        $this->execute("DROP TRIGGER IF EXISTS trg_tr_hist_before_update");

        $this->dropForeignKey('fk-trhist-warehouse', '{{%tr_hist}}');
        $this->dropForeignKey('fk-trhist-product', '{{%tr_hist}}');

        $this->dropIndex('idx-trhist-number', '{{%tr_hist}}');
        $this->dropIndex('idx-trhist-product', '{{%tr_hist}}');
        $this->dropIndex('idx-trhist-filter', '{{%tr_hist}}');

        $this->dropTable('{{%tr_hist}}');
    }
}

<?php

use yii\db\Migration;

class m260408_022632_create_table_x_stock_detail extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%stock_detail}}', [
            'iId' => $this->primaryKey(),
            'iProdukId' => $this->integer()->notNull(),
            'iGudangId' => $this->integer()->notNull(),
            'vBatch' => $this->string(50)->notNull(),
            'dExpired' => $this->date(),
            'iQty' => $this->integer()->notNull()->defaultValue(0)->comment('Qty Masuk'),
            'iQty2' => $this->integer()->defaultValue(0)->comment('Qty Keluar'),
            'iQtysum' => $this->integer()->comment('Total Qty'),
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
            'idx-stock-detail-product-gudang',
            '{{%stock_detail}}',
            ['iProdukId', 'iGudangId']
        );

        $this->createIndex(
            'idx-stock-detail-batch',
            '{{%stock_detail}}',
            ['vBatch', 'dExpired']
        );

        // FK (optional tapi recommended)
        $this->addForeignKey(
            'fk-stock-detail-product',
            '{{%stock_detail}}',
            'iProdukId',
            '{{%m_products}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-stock-detail-warehouse',
            '{{%stock_detail}}',
            'iGudangId',
            '{{%m_warehouse}}',
            'iId',
            'CASCADE',
            'CASCADE'
        );

        // =========================
        // TRIGGERS
        // =========================

        // BEFORE INSERT
        $this->execute("
            CREATE TRIGGER trg_stockdet_before_insert
            BEFORE INSERT ON {{%stock_detail}}
            FOR EACH ROW
            BEGIN
                SET NEW.iQtysum = NEW.iQty - NEW.iQty2;
            END
        ");

        // AFTER INSERT
        $this->execute("
            CREATE TRIGGER trg_stockdet_after_insert
            AFTER INSERT ON {{%stock_detail}}
            FOR EACH ROW
            BEGIN
                DECLARE iQtysumdet INT DEFAULT 0;

                SELECT SUM(IFNULL(iQtysum,0)) INTO iQtysumdet
                FROM {{%stock_detail}}
                WHERE iProdukId = NEW.iProdukId
                  AND iGudangId = NEW.iGudangId;

                UPDATE {{%stock}}
                SET iQty = iQtysumdet
                WHERE iProdukId = NEW.iProdukId
                  AND iGudangId = NEW.iGudangId;
            END
        ");

        // BEFORE UPDATE
        $this->execute("
            CREATE TRIGGER trg_stockdet_before_update
            BEFORE UPDATE ON {{%stock_detail}}
            FOR EACH ROW
            BEGIN
                SET NEW.iQtysum = NEW.iQty - NEW.iQty2;
            END
        ");

        // AFTER UPDATE
        $this->execute("
            CREATE TRIGGER trg_stockdet_after_update
            AFTER UPDATE ON {{%stock_detail}}
            FOR EACH ROW
            BEGIN
                DECLARE iQtysumdet INT DEFAULT 0;

                SELECT SUM(IFNULL(iQtysum,0)) INTO iQtysumdet
                FROM {{%stock_detail}}
                WHERE iProdukId = NEW.iProdukId
                  AND iGudangId = NEW.iGudangId;

                UPDATE {{%stock}}
                SET iQty = iQtysumdet
                WHERE iProdukId = NEW.iProdukId
                  AND iGudangId = NEW.iGudangId;
            END
        ");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        // drop triggers dulu
        $this->execute("DROP TRIGGER IF EXISTS trg_stockdet_before_insert");
        $this->execute("DROP TRIGGER IF EXISTS trg_stockdet_after_insert");
        $this->execute("DROP TRIGGER IF EXISTS trg_stockdet_before_update");
        $this->execute("DROP TRIGGER IF EXISTS trg_stockdet_after_update");

        $this->dropForeignKey('fk-stock-detail-warehouse', '{{%stock_detail}}');
        $this->dropForeignKey('fk-stock-detail-product', '{{%stock_detail}}');

        $this->dropIndex('idx-stock-detail-batch', '{{%stock_detail}}');
        $this->dropIndex('idx-stock-detail-product-gudang', '{{%stock_detail}}');

        $this->dropTable('{{%stock_detail}}');
    }
}

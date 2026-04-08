<?php

use yii\db\Migration;

class m260408_022557_create_table_x_products extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%m_products}}', [
            'iId' => $this->primaryKey(),
            'vNama' => $this->string(100)->notNull()->comment('Nama Barang'),
            'vSlug' => $this->string(150)->notNull(),
            'vImage' => $this->string(255)->comment('File name Image Produk'),
            'iStatus' => $this->integer()->notNull()->comment('Status Produk'),
            'iRak' => $this->integer(),
            'iKemasanBesarId' => $this->integer()->notNull(),
            'iIsiKemasanBesar' => $this->integer()->notNull()->defaultValue(1),
            'iKemasanKecilId' => $this->integer(),
            'iIsiKemasanKecil' => $this->integer(),
            'dPrice' => $this->decimal(14, 2)->notNull(),
            'dMargin' => $this->decimal(4, 2)->notNull(),
            'vDeskripsi' => $this->text(),
            'eDeleted' => "ENUM('Tidak','Ya') DEFAULT 'Tidak'",
            'tCreated' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'tUpdated' => $this->timestamp()
                ->defaultExpression('CURRENT_TIMESTAMP')
                ->append('ON UPDATE CURRENT_TIMESTAMP'),
            'iCreatedId' => $this->integer()->comment('User yang membuat data'),
            'iUpdatedId' => $this->integer()->comment('User yang modifikasi data'),
        ], $tableOptions);

        // index
        $this->createIndex('idx-m_products-price', '{{%m_products}}', ['dPrice', 'dMargin']);
        $this->createIndex('idx-m_products-kemasan', '{{%m_products}}', ['iKemasanBesarId', 'iKemasanKecilId']);
        $this->createIndex('idx-m_products-nama', '{{%m_products}}', ['vNama', 'eDeleted']);
        $this->createIndex('idx-m_products-status', '{{%m_products}}', ['iStatus', 'iRak']);

        // trigger (audit perubahan harga)
        $this->execute("
            CREATE TRIGGER trg_m_products_before_update
            BEFORE UPDATE ON {{%m_products}}
            FOR EACH ROW
            BEGIN
                IF OLD.dPrice <> NEW.dPrice THEN
                    INSERT INTO {{%h_product_price}} 
                    (iProdukId, oldPrice, newPrice, iUpdatedid)
                    VALUES (NEW.iId, OLD.dPrice, NEW.dPrice, NEW.iUpdatedid);
                END IF;
            END
        ");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        // drop trigger dulu
        $this->execute("DROP TRIGGER IF EXISTS trg_m_products_before_update");

        $this->dropIndex('idx-m_products-status', '{{%m_products}}');
        $this->dropIndex('idx-m_products-nama', '{{%m_products}}');
        $this->dropIndex('idx-m_products-kemasan', '{{%m_products}}');
        $this->dropIndex('idx-m_products-price', '{{%m_products}}');

        $this->dropTable('{{%m_products}}');
    }
}

<?php

use yii\db\Migration;

class m260408_021329_create_table_x_menu extends Migration
{
    public function safeUp()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');

        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'parent' => $this->integer(),
            'route' => $this->string(255),
            'order' => $this->integer(),
            'data' => $this->binary(),
            'level' => $this->integer(),
            'icon' => $this->string(50),
            'eparent' => "ENUM('Ya','Tidak') DEFAULT 'Tidak'",
        ], $tableOptions);

        // index parent
        $this->createIndex(
            'idx-menu-parent',
            '{{%menu}}',
            'parent'
        );

        // self FK (parent ke id di table yang sama)
        $this->addForeignKey(
            'fk-menu-parent',
            '{{%menu}}',
            'parent',
            '{{%menu}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // insert data
        $this->batchInsert(
            '{{%menu}}',
            ['id', 'name', 'parent', 'route', 'order', 'data', 'level', 'icon', 'eparent'],
            [
                [1, 'Dashboard', null, '/site/index', 1, null, 1, 'bi bi-grid-fill', 'Tidak'],
                [2, 'Administration', null, '/#', 2, null, 1, 'bi bi-server', 'Ya'],
                [3, 'RBAC Manager', 2, '/admin/menu/index', 1, null, 2, 'bi bi-signpost-2-fill', 'Tidak'],
                [4, 'Gii Modules', 2, '/gii', 2, null, 2, 'bi bi-door-closed-fill', 'Tidak'],
                [5, 'Master Data', null, '/#', 3, null, 1, 'bi bi-book-half', 'Ya'],
                [6, 'Master Products', 5, '/products/index', 1, null, 2, 'fas fa-box', 'Tidak'],
                [7, 'Master Warehouses', 5, '/warehouses/index', 2, null, 2, 'fas fa-warehouse', 'Tidak'],
                [8, 'Master Packaging', 5, '/packs/index', 3, null, 2, 'fas fa-cubes', 'Tidak'],
                [9, 'Users', 2, '/user/index', 3, null, 2, 'fas fa-users', 'Tidak'],
                [10, 'Master Rack', 5, '/rack/index', 4, null, 2, 'fas fa-archive', 'Tidak'],
                [11, 'Stock', null, '/stock/index', 4, null, 1, 'fas fa-boxes', 'Tidak'],
                [12, 'Adjustment', null, '/#', 5, null, 1, 'fas fa-exchange-alt', 'Ya'],
                [13, 'Adjustment Plus', 12, '/adjustment-plus/index', 1, null, 2, 'fas fa-plus-circle', 'Tidak'],
                [14, 'Adjustment Minus', 12, '/adjustment-minus/index', 2, null, 2, 'fas fa-minus-circle', 'Tidak'],
                [15, 'Transfer Stock', null, '/#', 6, null, 1, 'fas fa-dolly-flatbed', 'Ya'],
                [16, 'Transfer Stock In', 15, '/transfer-in/index', 1, null, 2, 'fas fa-arrow-alt-circle-down', 'Tidak'],
                [17, 'Transfer Stock Out', 15, '/transfer-out/index', 2, null, 2, 'fas fa-arrow-alt-circle-up', 'Tidak'],
            ]
        );

        // reset auto increment biar lanjut dari 18
        $this->execute("ALTER TABLE {{%menu}} AUTO_INCREMENT = 18");

        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-menu-parent', '{{%menu}}');
        $this->dropIndex('idx-menu-parent', '{{%menu}}');
        $this->dropTable('{{%menu}}');
    }
}

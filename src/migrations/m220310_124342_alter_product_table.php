<?php

use yii\db\Migration;

/**
 * Class m220310_124342_alter_product_table
 */
class m220310_124342_alter_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_product', 'is_inventory_independent',
            'TINYINT(1) NOT NULL DEFAULT 0 AFTER [[popularity]]');
        $this->alterColumn('sp_variant', 'stock', 'INT(11) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('sp_variant', 'stock', 'INT(11) NOT NULL');
        $this->dropColumn('sp_product', 'is_inventory_independent');
    }

}

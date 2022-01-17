<?php

use yii\db\Migration;

/**
 * Class m220117_140934_alter_variant_and_order_item_table
 */
class m220117_140934_alter_variant_and_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_variant', 'include_vat', 'TINYINT(1) NOT NULL DEFAULT 1 AFTER [[vat]]');
        $this->addColumn('sp_order_item', 'single_net_price', 'DECIMAL(10,2) NULL AFTER [[single_price]]');
        $this->addColumn('sp_order_item', 'vat', 'DECIMAL(10,2) NULL AFTER [[single_net_price]]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_variant', 'include_vat');
        $this->dropColumn('sp_order_item', 'single_net_price');
        $this->dropColumn('sp_order_item', 'vat');
    }
}

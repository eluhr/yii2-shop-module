<?php

use yii\db\Migration;

/**
 * Class m220112_084138_alter_product_variant_table
 */
class m220112_084138_alter_product_variant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_variant','discount_price','DECIMAL(10, 2) NULL AFTER [[price]]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_variant','discount_price');
    }
}

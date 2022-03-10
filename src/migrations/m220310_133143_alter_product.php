<?php

use yii\db\Migration;

/**
 * Class m220310_133143_alter_product
 */
class m220310_133143_alter_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_product', 'hide_in_overview',
            'TINYINT(1) NOT NULL DEFAULT 0 AFTER [[is_inventory_independent]]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_product', 'hide_in_overview');
    }
}

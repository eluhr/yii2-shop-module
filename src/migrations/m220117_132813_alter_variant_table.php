<?php

use yii\db\Migration;

/**
 * Class m220117_132813_alter_variant_table
 */
class m220117_132813_alter_variant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_variant', 'vat', 'DECIMAL(10,2) NULL AFTER [[discount_price]]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_variant', 'vat');
    }

}

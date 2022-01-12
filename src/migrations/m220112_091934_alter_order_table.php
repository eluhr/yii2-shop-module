<?php

use yii\db\Migration;

/**
 * Class m220112_091934_alter_order_table
 */
class m220112_091934_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_order', 'customer_details', 'TEXT NULL AFTER [[internal_notes]]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_order', 'customer_details');
    }

}

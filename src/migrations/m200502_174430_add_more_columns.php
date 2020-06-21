<?php

use yii\db\Migration;

/**
 * Class m200502_174430_add_more_columns
 */
class m200502_174430_add_more_columns extends Migration
{
    public function up()
    {
        $this->addColumn('sp_order', 'shipping_price', 'DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER paid');
    }

    public function down()
    {
        echo "m200502_174430_add_more_columns cannot be reverted.\n";
        return false;
    }
}

<?php

use yii\db\Migration;

/**
 * Class m200501_200159_add_missing_stuff
 */
class m200501_200159_add_missing_stuff extends Migration
{
    public function up()
    {
        $this->addColumn('sp_order', 'shipment_link', 'VARCHAR(128) NULL AFTER status');
        $this->addColumn('sp_product', 'shipping_price', 'DECIMAL(10,2) NOT NULL DEFAULT 0 AFTER rank');
    }

    public function down()
    {
        $this->dropColumn('sp_order', 'shipment_link');
        $this->dropColumn('sp_product', 'shipping_price');
    }
}

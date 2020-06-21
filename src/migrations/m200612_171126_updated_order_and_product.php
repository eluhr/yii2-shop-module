<?php

use yii\db\Migration;

/**
 * Class m200612_171126_updated_order_and_product
 */
class m200612_171126_updated_order_and_product extends Migration
{
    public function up()
    {
        $this->addColumn('sp_product', 'staggering_shipping_cost', 'TINYINT NULL DEFAULT 0 AFTER shipping_price');
        $this->addColumn('sp_order', 'has_different_delivery_address', 'TINYINT NULL DEFAULT 0 AFTER city');
        $this->addColumn('sp_order', 'delivery_first_name', 'VARCHAR(45) NULL AFTER has_different_delivery_address');
        $this->addColumn('sp_order', 'delivery_surname', 'VARCHAR(45) NULL AFTER delivery_first_name');
        $this->addColumn('sp_order', 'delivery_street_name', 'VARCHAR(45) NULL AFTER delivery_surname');
        $this->addColumn('sp_order', 'delivery_house_number', 'VARCHAR(45) NULL AFTER delivery_street_name');
        $this->addColumn('sp_order', 'delivery_postal', 'VARCHAR(45) NULL AFTER delivery_house_number');
        $this->addColumn('sp_order', 'delivery_city', 'VARCHAR(45) NULL AFTER delivery_postal');
    }

    public function down()
    {
        echo "m200612_171126_updated_order_and_product cannot be reverted.\n";
//        return false;
    }
}

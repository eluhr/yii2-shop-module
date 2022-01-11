<?php

use yii\db\Migration;

/**
 * Class m220111_134912_alter_product_variants_table
 */
class m220111_134912_alter_product_variants_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('sp_variant', 'min_days_shipping_duration','INT NULL AFTER [[extra_info]]');
        $this->addColumn('sp_variant', 'max_days_shipping_duration','INT NULL AFTER [[min_days_shipping_duration]]');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('sp_variant', 'min_days_shipping_duration');
        $this->dropColumn('sp_variant', 'max_days_shipping_duration');
    }
}

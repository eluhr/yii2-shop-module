<?php

use yii\db\Migration;

/**
 * Class m200504_073006_add_sku_to_variant
 */
class m200504_073006_add_sku_to_variant extends Migration
{
    public function up()
    {
        $this->addColumn('sp_variant', 'sku', 'VARCHAR(128) NULL AFTER stock');
    }

    public function down()
    {
        echo "m200504_073006_add_sku_to_variant cannot be reverted.\n";
        return false;
    }
}

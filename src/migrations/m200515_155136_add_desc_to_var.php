<?php

use yii\db\Migration;

/**
 * Class m200515_155136_add_desc_to_var
 */
class m200515_155136_add_desc_to_var extends Migration
{
    public function up()
    {
        $this->addColumn('sp_variant', 'description', 'TEXT NULL AFTER sku');
    }

    public function down()
    {
        echo "m200515_155136_add_desc_to_var cannot be reverted.\n";
        return false;
    }
}

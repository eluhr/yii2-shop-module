<?php

use yii\db\Migration;

/**
 * Class m200608_202831_update
 */
class m200608_202831_update extends Migration
{
    public function up()
    {
        $this->addColumn('sp_order', 'is_executed', 'TINYINT NULL DEFAULT 0 AFTER paypal_payer_id');
    }

    public function down()
    {
        echo "m200608_202831_update cannot be reverted.\n";
        return false;
    }
}

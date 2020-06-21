<?php

use yii\db\Migration;

/**
 * Class m200502_182110_add_column
 */
class m200502_182110_add_column extends Migration
{
    public function up()
    {
        $this->alterColumn('sp_order', 'status', "ENUM('PENDING', 'RECEIVED','RECEIVED PAID','IN PROGRESS','SHIPPED','FINISHED') NOT NULL DEFAULT 'RECEIVED'");
    }

    public function down()
    {
        echo "m200502_182110_add_column cannot be reverted.\n";
        return false;
    }
}

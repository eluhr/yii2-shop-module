<?php

use yii\db\Migration;

/**
 * Class m200520_152841_add_column_to_order
 */
class m200520_152841_add_column_to_order extends Migration
{
    public function up()
    {
        $this->addColumn('sp_order', 'info_mail_has_been_sent', 'TINYINT NOT NULL DEFAULT 0 AFTER discount_code_id');
    }

    public function down()
    {
        echo "m200520_152841_add_column_to_order cannot be reverted.\n";
        return false;
    }
}

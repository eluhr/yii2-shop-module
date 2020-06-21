<?php

use yii\db\Migration;

/**
 * Class m200610_163245_add_internal_notes_for_orders
 */
class m200610_163245_add_internal_notes_for_orders extends Migration
{
    public function up()
    {
        $this->addColumn('sp_order', 'internal_notes', 'TEXT NULL AFTER date_of_birth');
    }

    public function down()
    {
        echo "m200610_163245_add_internal_notes_for_orders cannot be reverted.\n";
        return false;
    }
}

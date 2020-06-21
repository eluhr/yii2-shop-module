<?php

use yii\db\Migration;

/**
 * Class m200609_130921_add_date_of_birth
 */
class m200609_130921_add_date_of_birth extends Migration
{
    public function up()
    {
        $this->addColumn('sp_order', 'date_of_birth', 'DATE NULL AFTER is_executed');
    }

    public function down()
    {
        echo "m200609_130921_add_date_of_birth cannot be reverted.\n";
        return false;
    }
}

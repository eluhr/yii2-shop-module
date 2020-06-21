<?php

use yii\db\Migration;

/**
 * Class m200405_133235_alter_stuff
 */
class m200405_133235_alter_stuff extends Migration
{
    public function up()
    {
        $this->addColumn('sp_tag_x_product', 'created_at', 'DATETIME');
        $this->addColumn('sp_tag_x_product', 'updated_at', 'DATETIME');
    }

    public function down()
    {
        echo "m200405_133235_alter_stuff cannot be reverted.\n";
        return false;
    }
}

<?php

use yii\db\Migration;

/**
 * Class m211008_133828_alter_order_table
 */
class m211008_133828_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->alterColumn('sp_order', 'type', 'VARCHAR(80) NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m211008_133828_alter_order_table cannot be reverted.\n";
        return false;
    }

}

<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220328_110948_alter_order_table
 */
class m220328_110948_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_order','user_id',Schema::TYPE_INTEGER . ' NULL AFTER [[id]]');
        $this->addForeignKey('fk_order_user_ui_0','sp_order','user_id','{{%user}}','id','SET NULL','CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_order','user_id');
    }
}

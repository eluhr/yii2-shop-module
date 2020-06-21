<?php

use yii\db\Migration;

/**
 * Class m200521_173957_add_shop_settings_table
 */
class m200521_173957_add_shop_settings_table extends Migration
{
    public function up()
    {
        $this->createTable('sp_settings', [
            'key' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'PRIMARY KEY (`key`)'
        ]);
    }

    public function down()
    {
        $this->dropTable('sp_settings');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m200521_173957_add_shop_settings_table
 */
class m200521_173957_add_shop_settings_table extends Migration
{
    public function up()
    {
        $tableOptions = $this->getDb()->getDriverName() === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable('sp_settings', [
            'key' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'PRIMARY KEY (`key`)'
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('sp_settings');
    }
}

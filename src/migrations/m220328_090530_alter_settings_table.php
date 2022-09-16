<?php

use yii\db\Migration;

/**
 * Class m220328_090530_alter_settings_table
 */
class m220328_090530_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('sp_settings', 'value', 'VARCHAR(255) NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('sp_settings', 'value', 'VARCHAR(255) NOT NULL');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m220329_081001_alter_discount_table
 */
class m220329_081001_alter_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_discount_code', 'type', 'INT NOT NULL DEFAULT 1 AFTER [[id]]');
        $this->renameColumn('sp_discount_code', 'percent', 'value');
        $this->alterColumn('sp_discount_code','value', 'DECIMAL(10,2) UNSIGNED NOT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_discount_code', 'type');
        $this->renameColumn('sp_discount_code', 'value', 'percent');
        $this->alterColumn('sp_discount_code','percent', 'DECIMAL(10,2) UNSIGNED NOT NULL');
    }
}

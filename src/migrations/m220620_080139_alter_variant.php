<?php

use yii\db\Migration;

/**
 * Class m220620_080139_alter_variant
 */
class m220620_080139_alter_variant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_variant', 'configurator_url', $this->string(80)->null()->after('max_days_shipping_duration'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220620_080139_alter_variant cannot be reverted.\n";
        return false;
    }
}

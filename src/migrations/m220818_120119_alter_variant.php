<?php

use yii\db\Migration;

/**
 * Class m220818_120119_alter_variant
 */
class m220818_120119_alter_variant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_variant', 'configurator_bg_image', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220818_120119_alter_variant cannot be reverted.\n";
        return false;
    }
}

<?php

use yii\db\Migration;

/**
 * Class m220818_120119_alter_variant
 */
class m220926_120119_variant_configurator_data_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('sp_variant', 'configurator_bg_image');
        $this->addColumn('sp_variant', 'configurator_data', 'LONGTEXT');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_variant', 'configurator_data');
        $this->addColumn('sp_variant', 'configurator_bg_image', $this->string(255)->null());
        return false;
    }
}

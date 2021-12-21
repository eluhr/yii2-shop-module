<?php

use yii\db\Migration;

/**
 * Class m211221_102723_add_extra_info_to_variant
 */
class m211221_102723_add_extra_info_to_variant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_variant', 'extra_info', 'VARCHAR(128) NULL AFTER description');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_variant', 'extra_info');
    }
}

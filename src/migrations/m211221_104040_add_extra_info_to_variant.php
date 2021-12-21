<?php

use yii\db\Migration;

/**
 * Class m211221_104040_add_extra_info_to_variant
 */
class m211221_104040_add_extra_info_to_variant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('sp_order_item', 'extra_info', 'VARCHAR(128) NOT NULL DEFAULT "-" AFTER quantity');
        $this->execute('ALTER TABLE sp_order_item DROP PRIMARY KEY;');
        $this->addPrimaryKey('PK_order_item', 'sp_order_item', [
            'order_id',
            'variant_id',
            'extra_info'
        ]);
    }

    public function down()
    {
        $this->dropColumn('sp_order_item', 'extra_info');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m211221_104040_add_extra_info_to_variant
 */
class m220825_103020_config_json_to_order_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('sp_order_item', 'configuration_json', 'LONGTEXT');
    }

    public function down()
    {
        $this->dropColumn('sp_order_item', 'configuration_json');
    }
}

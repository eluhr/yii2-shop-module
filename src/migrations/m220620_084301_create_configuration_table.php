<?php

use yii\db\Migration;

/**
 * Handles the creation of table `sp_configuration`.
 */
class m220620_084301_create_configuration_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $options = $this->getDb()->getDriverName() === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci' : null;
        $this->createTable('sp_configuration', [
            'id' => $this->string(36)->notNull(),
            'variant_id' => $this->integer()->notNull(),
            'json' => $this->text()->notNull(),
            'PRIMARY KEY ([[id]])'
        ], $options);
        $this->addForeignKey('FK_configuration_variant_0', 'sp_configuration', 'variant_id', 'sp_variant', 'id',
            'CASCADE', 'CASCADE');

        $this->addColumn('sp_order_item','configuration_id', $this->string(36)->null()->after('variant_id'));

        $this->addForeignKey('FK_order_item_configuration_0', 'sp_order_item', 'configuration_id', 'sp_configuration', 'id',
            'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_order_item','configuration_id');
        $this->dropTable('sp_configuration');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m220330_072211_alter_variant_table
 */
class m220330_072211_alter_variant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sp_variant','show_affiliate_link','TINYINT(1) NOT NULL DEFAULT 0 AFTER [[sku]]');
        $this->addColumn('sp_variant','affiliate_link_url','VARCHAR(255) NULL AFTER [[show_affiliate_link]]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sp_variant','show_affiliate_link');
        $this->dropColumn('sp_variant','affiliate_link_url');
    }
}

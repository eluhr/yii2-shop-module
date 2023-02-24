<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vat}}`.
 */
class m230224_120001_add_vat_ref_col_2_variant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {

        $this->addColumn('sp_variant', 'vat_id', 'INT NULL AFTER vat');
        $this->addForeignKey('fk_variant_2_vat', 'sp_variant', 'vat_id', 'sp_vat', 'id', 'NO ACTION', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('fk_variant_2_vat', 'sp_variant');
        $this->dropColumn('sp_variant', 'vat_id');
    }
}

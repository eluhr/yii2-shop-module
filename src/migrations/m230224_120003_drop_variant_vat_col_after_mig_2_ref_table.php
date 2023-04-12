<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vat}}`.
 */
class m230224_120003_drop_variant_vat_col_after_mig_2_ref_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->dropColumn('sp_variant', 'vat');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo "m230224_120003_drop_variant_vat_col_after_mig_2_ref_table cannot be reverted.\n";
        return false;
    }
}

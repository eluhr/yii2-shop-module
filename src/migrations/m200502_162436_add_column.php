<?php

use yii\db\Migration;

/**
 * Class m200502_162436_add_column
 */
class m200502_162436_add_column extends Migration
{
    public function up()
    {
        $this->addColumn('sp_order', 'type', 'ENUM("PAYPAL","PREPAYMENT") NOT NULL DEFAULT "PAYPAL" AFTER paid');
        $this->alterColumn('sp_order', 'paypal_id', 'VARCHAR(45) NULL');
        $this->addColumn('sp_order', 'invoice_number', 'VARCHAR(128) NULL AFTER type');
        $this->createIndex('uq_o_in_0', 'sp_order', 'invoice_number', true);
    }

    public function down()
    {
        $this->dropColumn('sp_order', 'type');
        $this->dropColumn('sp_order', 'invoice_number');
        $this->alterColumn('sp_order', 'paypal_id', 'VARCHAR(45) NOT NULL');
    }
}

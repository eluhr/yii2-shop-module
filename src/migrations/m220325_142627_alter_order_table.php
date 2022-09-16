<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m220325_142627_alter_order_table
 */
class m220325_142627_alter_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('sp_order', 'payment_details', 'TEXT NULL AFTER [[paypal_payer_id]]');
        $this->execute("UPDATE sp_order SET payment_details = JSON_OBJECT('paymentId', paypal_id, 'token', paypal_token, 'PayerID', paypal_payer_id);");
        $this->dropColumn('sp_order','paypal_id');
        $this->dropColumn('sp_order','paypal_token');
        $this->dropColumn('sp_order','paypal_payer_id');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        echo self::class . ' cannot be reverted' . PHP_EOL;
       return false;
    }

}

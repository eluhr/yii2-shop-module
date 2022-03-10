<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discout_code}}`.
 */
class m200313_163717_create_discount_code_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = $this->getDb()->getDriverName() === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable('sp_discount_code', [
            'id' => $this->primaryKey(),
            'code' => $this->string(128)->notNull()->unique(),
            'percent' => $this->decimal(10, 2)->notNull(),
            'expiration_date' => $this->date()->notNull(),
            'used' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addColumn('sp_order', 'discount_code_id', 'INT NULL AFTER paypal_payer_id');
        $this->addForeignKey('fk_order_discount', 'sp_order', 'discount_code_id', 'sp_discount_code', 'id', 'SET NULL', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('sp_discount_code');
        $this->dropForeignKey('fk_order_discount', 'sp_order');
        $this->dropColumn('sp_order', 'discount_code_id');
    }
}

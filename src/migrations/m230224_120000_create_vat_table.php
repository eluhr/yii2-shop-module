<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%vat}}`.
 */
class m230224_120000_create_vat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = $this->getDb()->getDriverName() === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;
        $this->createTable('sp_vat', [
            'id' => $this->primaryKey(),
            'value' => $this->decimal(10, 2)->notNull()->unique(),
            'desc' => $this->string(255)->null(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('sp_vat');
    }
}

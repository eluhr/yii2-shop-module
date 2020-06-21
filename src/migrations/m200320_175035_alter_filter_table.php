<?php

use yii\db\Migration;

/**
 * Class m200320_175035_alter_filter_table
 */
class m200320_175035_alter_filter_table extends Migration
{
    public function up()
    {
        $this->addColumn('sp_filter', 'rank', 'INT NOT NULL DEFAULT 0 AFTER name');
        $this->addColumn('sp_filter', 'presentation', 'ENUM("dropdown", "radios") NOT NULL DEFAULT "dropdown" AFTER rank');

        $this->addColumn('sp_tag_x_filter', 'rank', 'INT NOT NULL DEFAULT 0 AFTER show_in_frontend');
        $this->addColumn('sp_tag_x_filter', 'created_at', 'DATETIME NULL AFTER rank');
        $this->addColumn('sp_tag_x_filter', 'updated_at', 'DATETIME NULL AFTER created_at');

        $this->addColumn('sp_product', 'popularity', 'INT NOT NULL DEFAULT 0 AFTER description');
    }

    public function down()
    {
        echo "m200320_175035_alter_filter_table cannot be reverted.\n";
        return false;
    }
}

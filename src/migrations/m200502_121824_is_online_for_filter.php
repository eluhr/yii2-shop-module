<?php

use yii\db\Migration;

/**
 * Class m200502_121824_is_online_for_filter
 */
class m200502_121824_is_online_for_filter extends Migration
{
    public function up()
    {
        $this->addColumn('sp_filter', 'is_online', 'TINYINT(1) NOT NULL DEFAULT 1');
    }

    public function down()
    {
        $this->dropColumn('sp_filter', 'is_online');
    }
}

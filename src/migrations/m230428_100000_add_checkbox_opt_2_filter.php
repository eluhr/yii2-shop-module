<?php

use yii\db\Migration;

/**
 * Class m230428_100000_add_checkbox_opt_2_filter
 *
 * this migration adds the checkbox option to the filter.presentation enum
 *
 */
class m230428_100000_add_checkbox_opt_2_filter extends Migration
{
    public function up()
    {
        $this->alterColumn('sp_filter', 'presentation', 'ENUM("dropdown", "radios", "checkbox") NOT NULL DEFAULT "dropdown"');
    }

    public function down()
    {
        $this->alterColumn('sp_filter', 'presentation', 'ENUM("dropdown", "radios") NOT NULL DEFAULT "dropdown"');
    }
}

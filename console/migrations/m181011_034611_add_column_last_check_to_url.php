<?php

use yii\db\Migration;

/**
 * Class m181011_034611_add_column_last_check_to_url
 */
class m181011_034611_add_column_last_check_to_url extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('url', 'last_check',
            $this->integer(11)->defaultValue(0)->after('expected_response'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('url', 'last_check');
    }
}

<?php

use yii\db\Migration;

/**
 * Class m181017_120131_add_columns_create_and_update_to_user_agent
 */
class m181017_120131_add_columns_create_and_update_to_user_agent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_agent', 'created_at', $this->integer()->notNull());
        $this->addColumn('user_agent', 'updated_at', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_agent', 'created_at');
        $this->dropColumn('user_agent', 'updated_at');
    }

}

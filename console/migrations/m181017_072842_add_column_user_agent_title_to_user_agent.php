<?php

use yii\db\Migration;

/**
 * Class m181017_072842_add_column_user_agent_title_to_user_agent
 */
class m181017_072842_add_column_user_agent_title_to_user_agent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_agent', 'user_agent_title',
            $this->string(255)->notNull()->unique()->after('id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_agent', 'user_agent_title');
    }
}

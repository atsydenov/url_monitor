<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m181015_060807_add_column_role_to_user
 */
class m181015_060807_add_column_role_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'role', $this->string(50)->defaultValue(User::ROLE_USER));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'role');
    }

}

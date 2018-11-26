<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m181017_070814_add_admin_user
 */
class m181017_070814_add_admin_user extends Migration
{
    /**
     * {@inheritdoc}
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $this->insert('user', [
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('qwerty'),
            'email' => 'admin@example.com',
            'telegram_id' => 123456789,
            'role' => User::ROLE_ADMINISTRATOR,
            'status' => User::STATUS_ACTIVE,
            'created_at' => '0',
            'updated_at' => '0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('user', ['username' => 'admin', 'created_at' => 0]);
    }
}

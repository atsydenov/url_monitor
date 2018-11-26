<?php

use yii\db\Migration;

/**
 * Class m181007_134006_add_column_telegram_id_to_user
 */
class m181007_134006_add_column_telegram_id_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'telegram_id',
            $this->string(255)->notNull()->unique()->after('email'));

        $this->createIndex(
            'idx-telegram_id',
            'user',
            'telegram_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-telegram_id',
            'user'
        );

        $this->dropColumn('user', 'telegram_id');
    }
}

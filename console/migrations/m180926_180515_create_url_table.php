<?php

use yii\db\Migration;

/**
 * Handles the creation of table `url`.
 */
class m180926_180515_create_url_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('url', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'url_title' => $this->string(255)->notNull(),
            'url' => $this->string(255)->notNull(),
            'active' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'check_interval' => $this->integer(10)->notNull(),
            'user_agent_id' => $this->integer()->notNull(),
            'request_type' => "ENUM('head', 'get', 'post') NOT NULL DEFAULT 'head'",
            'expected_response' => $this->string(255)->notNull()->defaultValue(200),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-user_id',
            'url',
            'user_id'
        );

        $this->addForeignKey(
            'fk-user_id',
            'url',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-user_agent_id',
            'url',
            'user_agent_id'
        );

        $this->addForeignKey(
            'fk-user_agent_id',
            'url',
            'user_agent_id',
            'user_agent',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_id',
            'url'
        );

        $this->dropIndex(
            'idx-user_id',
            'url'
        );

        $this->dropForeignKey(
            'fk-user_agent_id',
            'url'
        );

        $this->dropIndex(
            'idx-user_agent_id',
            'url'
        );

        $this->dropTable('url');
    }
}

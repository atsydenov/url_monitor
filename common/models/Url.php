<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "url".
 *
 * @property int $id
 * @property int $user_id
 * @property string $url_title
 * @property string $url
 * @property int $active
 * @property int $check_interval
 * @property int $user_agent_id
 * @property string $request_type
 * @property string $expected_response
 * @property int $last_check
 * @property int $created_at
 * @property int $updated_at
 *
 * @property UserAgent $userAgent
 * @property User $user
 */
class Url extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'url';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url_title', 'url', 'active', 'check_interval', 'user_agent_id', 'request_type', 'expected_response'], 'required'],
            [['user_id', 'active', 'check_interval', 'created_at', 'updated_at'], 'integer'],
            [['request_type'], 'string'],
            [['url_title', 'url', 'expected_response'], 'string', 'max' => 255],
            [['user_agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAgent::className(), 'targetAttribute' => ['user_agent_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'url_title' => 'Url Title',
            'url' => 'Url',
            'active' => 'Active',
            'check_interval' => 'Period (min)',
            'user_agent_id' => 'User Agent',
            'request_type' => 'Request',
            'expected_response' => 'Expected Responses',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAgent()
    {
        return $this->hasOne(UserAgent::className(), ['id' => 'user_agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}

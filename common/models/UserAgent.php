<?php

namespace common\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "user_agent".
 *
 * @property int $id
 * @property string $user_agent_title
 * @property string $user_agent
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Url[] $urls
 */
class UserAgent extends ActiveRecord
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
        return 'user_agent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_agent_title'], 'required'],
            [['user_agent_title'], 'string', 'max' => 255],
            [['user_agent_title'], 'unique'],
            [['user_agent'], 'required'],
            [['user_agent'], 'string', 'max' => 255],
            [['user_agent'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_agent_title' => 'Short Name',
            'user_agent' => 'User Agent',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUrls()
    {
        return $this->hasMany(Url::className(), ['user_agent_id' => 'id']);
    }

    /**
     * @return array Формируем выпадающий список для выбора user agent
     */
    public static function getUserAgentList()
    {
        $userAgentsList = UserAgent::find()->asArray()->all();
        $result = [];
        if (is_array($userAgentsList) && count($userAgentsList) > 0)
        {
            foreach ($userAgentsList as $userAgent)
            {
                $result[$userAgent['id']] = $userAgent['user_agent_title'];
            }
        }
        return $result;
    }

    /**
     * Полное название агента по id.
     *
     * @param $id
     * @return string
     */
    public function getUserAgentByID($id)
    {
        $userAgent = UserAgent::findOne(['id' => $id]);
        return $userAgent->user_agent;
    }
}

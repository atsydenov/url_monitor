<?php
namespace backend\models;

use common\models\User;
use Yii;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    public $password;
    public $password_repeat;
    public $id;

    public function rules()
    {
        return [
            [['password', 'password_repeat', 'id'], 'required'],
            ['password', 'string', 'min' => 6],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message'=> 'Passwords do not match'],
            ['id', 'exist',
                'targetClass' => '\common\models\User',
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'User not found'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Password',
            'password_repeat' => 'Change password',
        ];
    }

    public function save()
    {
        if (!$this->validate())
        {
            return false;
        }
        $model = User::findOne($this->id);
        $model->setPassword($this->password);
        $result = $model->save();
        if ($result)
        {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Password change successfully'));
        }
        return $result;
    }

}
<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $telegram_id
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $role
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    const ROLE_USER = 'user';
    const ROLE_ADMINISTRATOR = 'admin';

    const PERMISSION_URL_LIST = 'urlList';
    const PERMISSION_URL_CREATE = 'urlCreate';
    const PERMISSION_URL_UPDATE = 'urlUpdate';
    const PERMISSION_URL_DELETE = 'urlDelete';

    const PERMISSION_USER_AGENT_LIST = 'userAgentList';
    const PERMISSION_USER_AGENT_CREATE = 'userAgentCreate';
    const PERMISSION_USER_AGENT_UPDATE = 'userAgentUpdate';
    const PERMISSION_USER_AGENT_DELETE = 'userAgentDelete';

    const PERMISSION_USER_LIST = 'userList';
    const PERMISSION_USER_CREATE = 'userCreate';
    const PERMISSION_USER_UPDATE = 'userUpdate';
    const PERMISSION_USER_DELETE = 'userDelete';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['telegram_id', 'required', 'message' => 'Telegram ID cannot be blank.'],
            ['telegram_id', 'is6NumbersOnly'],
            ['telegram_id', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This telegram id has already been taken.'],

            ['status', 'default', 'value' => self::STATUS_DELETED],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            ['role', 'in', 'range' => [self::ROLE_USER, self::ROLE_ADMINISTRATOR]],
            [['role'], 'string', 'max' => 50],
            # Аналог метода beforeSave.
            # Если раскомментировать это, то можно убрать метод beforeSave.
            # В CIZ такой строчки не нашёл.
//            [['role'], 'default', 'value' => self::ROLE_USER],
        ];
    }

    public function is6NumbersOnly($attribute)
    {
        if (!preg_match('/^[0-9]{6,10}$/', $this->$attribute))
        {
            $this->addError($attribute, 'Telegram ID should contain at least 6 and no more 10 digits.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'telegram_id' => 'Telegram ID'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     * @throws \yii\base\NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Перед сохранением для нового пользователя явно присваиваем полю role значению user.
     *
     * @param bool $insert whether this method called while inserting a record.
     * If `false`, it means the method is called while updating a record.
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (empty($this->role))
        {
            $this->role = self::ROLE_USER;
        }
        # Это из CIZ:
        # TODO: Change the auto generated stub
        return parent::beforeSave($insert);
    }

    /**
     * После сохранения для нового пользователя присваиваем ему роль.
     *
     * @param @param bool $insert whether this method called while inserting a record.
     * If `false`, it means the method is called while updating a record.
     * @param array $changedAttributes The old values of attributes that had changed and were saved.
     * You can use this parameter to take action based on the changes made for example send an email
     * when the password had changed or implement audit trail that tracks all the changes.
     * `$changedAttributes` gives you the old attribute values while the active record (`$this`) has
     * already the new, updated values.
     * @throws \Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        if (!$insert)
        {
            Yii::$app->authManager->revokeAll($this->id);
        }

        $role = Yii::$app->authManager->getRole($this->role);
        Yii::$app->authManager->assign($role, $this->id);

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Список возможных статусов пользователей.
     */
    public static function statusList()
    {
        return [
            self::STATUS_ACTIVE => 'activated',
            self::STATUS_DELETED => 'inactivated',
        ];
    }

    /**
     * Список возможных ролей пользователей.
     */
    public static function roleList()
    {
        return [
            self::ROLE_USER => 'user',
            self::ROLE_ADMINISTRATOR => 'admin',
        ];
    }
}

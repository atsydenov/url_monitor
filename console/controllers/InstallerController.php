<?php

namespace console\controllers;

use yii\console\Controller;
use Yii;
use common\models\User;
use yii\console\Exception;

class InstallerController extends Controller
{
    /**
     * Создание ролей и разрешений.
     * Присвоение ролей пользователям.
     *
     */
    public function actionInitRoles()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $roleUser = $auth->createRole(User::ROLE_USER);
        $roleUser->description = User::ROLE_USER;
        $auth->add($roleUser);

        $roleAdmin = $auth->createRole(User::ROLE_ADMINISTRATOR);
        $roleAdmin->description = User::ROLE_ADMINISTRATOR;
        $auth->add($roleAdmin);
        $auth->addChild($roleAdmin, $roleUser);

        $urlListPermission = $auth->createPermission(User::PERMISSION_URL_LIST);
        $auth->add($urlListPermission);
        $auth->addChild($roleUser, $urlListPermission);

        $urlCreatePermission = $auth->createPermission(User::PERMISSION_URL_CREATE);
        $auth->add($urlCreatePermission);
        $auth->addChild($roleUser, $urlCreatePermission);

        $urlUpdatePermission = $auth->createPermission(User::PERMISSION_URL_UPDATE);
        $auth->add($urlUpdatePermission);
        $auth->addChild($roleUser, $urlUpdatePermission);

        $urlDeletePermission = $auth->createPermission(User::PERMISSION_URL_DELETE);
        $auth->add($urlDeletePermission);
        $auth->addChild($roleUser, $urlDeletePermission);

        $userAgentListPermission = $auth->createPermission(User::PERMISSION_USER_AGENT_LIST);
        $auth->add($userAgentListPermission);
        $auth->addChild($roleUser, $userAgentListPermission);

        $userAgentCreatePermission = $auth->createPermission(User::PERMISSION_USER_AGENT_CREATE);
        $auth->add($userAgentCreatePermission);
        $auth->addChild($roleAdmin, $userAgentCreatePermission);

        $userAgentUpdatePermission = $auth->createPermission(User::PERMISSION_USER_AGENT_UPDATE);
        $auth->add($userAgentUpdatePermission);
        $auth->addChild($roleAdmin, $userAgentUpdatePermission);

        $userAgentDeletePermission = $auth->createPermission(User::PERMISSION_USER_AGENT_DELETE);
        $auth->add($userAgentDeletePermission);
        $auth->addChild($roleAdmin, $userAgentDeletePermission);

        $userListPermission = $auth->createPermission(User::PERMISSION_USER_LIST);
        $auth->add($userListPermission);
        $auth->addChild($roleAdmin, $userListPermission);

        $userCreatePermission = $auth->createPermission(User::PERMISSION_USER_CREATE);
        $auth->add($userCreatePermission);
        $auth->addChild($roleAdmin, $userCreatePermission);

        $userUpdatePermission = $auth->createPermission(User::PERMISSION_USER_UPDATE);
        $auth->add($userUpdatePermission);
        $auth->addChild($roleAdmin, $userUpdatePermission);

        $userDeletePermission = $auth->createPermission(User::PERMISSION_USER_DELETE);
        $auth->add($userDeletePermission);
        $auth->addChild($roleAdmin, $userDeletePermission);

        $users = User::findAll(['status' => User::STATUS_ACTIVE]);
        foreach ($users as $user)
        {
            $userRole = $auth->getRole($user->getRole());
            $auth->assign($userRole, $user->getId());
        }
    }

    /**
     * Изменение пароля для пользователя.
     *
     * @param $login
     * @throws \yii\base\Exception
     */
    public function actionSetPassword($login)
    {
        /**
         * @var User $user
         */
        $user = User::find()->where(['username' => $login])->one();
        if (empty($user))
        {
            throw new Exception('User not found');
        }

        if ($this->confirm("Set password {$login}?", false))
        {
            $password = $this->prompt('Password:', ['required' => true]);
            $user->setPassword($password);
            if ($user->save())
            {
                echo 'Password changed successfully.' . "\n";
            }
            else
            {
                throw new Exception('Error update model ' . json_encode($user->getErrors()));
            }
        }
    }
}
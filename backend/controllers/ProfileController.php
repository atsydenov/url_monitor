<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * Методы actionUpdate, actionChangePassword см. в UserController.
 *
 * @package backend\controllers
 */
class ProfileController extends UserController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Profile of user.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex()
    {
        $userID = Yii::$app->user->id;
        return $this->render('index', [
            'model' => $this->findModel($userID),
        ]);
    }

    /**
     * Определяем метод для избежания ошибок.
     *
     * @param integer $id
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id = null)
    {
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Определяем метод для избежания ошибок.
     *
     * @param integer $id
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id = null)
    {
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Определяем метод для избежания ошибок.
     *
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreate()
    {
        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
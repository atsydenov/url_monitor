<?php

namespace backend\controllers;

use common\models\UserAgent;
use Yii;
use common\models\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\QueueTasks\MonitorUrl;
use common\models\User;
use yii\filters\AccessControl;
use common\models\UrlSearch;

/**
 * UrlController implements the CRUD actions for Url model.
 */
class UrlController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'permissions' => [User::PERMISSION_URL_LIST],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'permissions' => [User::PERMISSION_URL_LIST],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'permissions' => [User::PERMISSION_URL_LIST],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'permissions' => [User::PERMISSION_URL_LIST],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'permissions' => [User::PERMISSION_URL_LIST],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Url models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UrlSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Url model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        # Получаем название user agent по id
        $userAgent = new UserAgent();
        $userAgentName = $userAgent->getUserAgentByID($this->findModel($id)->user_agent_id);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'userAgentName' => $userAgentName,
        ]);
    }

    /**
     * Creates a new Url model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Url();

        # Значения по умолчанию
        $model->expected_response = '200';
        $model->user_id = Yii::$app->user->id;

        # Выпадающий список для user agents
        $userAgentsList = UserAgent::getUserAgentList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'userAgentsList' => $userAgentsList,
        ]);
    }

    /**
     * Updates an existing Url model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        # Выпадающий список для user agents
        $userAgentsList = UserAgent::getUserAgentList();

        return $this->render('update', [
            'model' => $model,
            'userAgentsList' => $userAgentsList,
        ]);
    }

    /**
     * Deletes an existing Url model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Url model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Url the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Url::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionMonitor()
    {
        $urlList = Url::find()->where(['Active' => 1])->asArray()->all();

        foreach ($urlList as $url)
        {
            # Получаем название user agent по id
            $userAgent = new UserAgent();
            $userAgentName = $userAgent->getUserAgentByID($url['user_agent_id']);

            # Добавляем ссылку на мониторинг в очередь
            Yii::$app->queue->delay($url['check_interval'])->push(new MonitorUrl([
                'url' => $url['url'],
                'userAgent' => $userAgentName,
                'requestType' => strtoupper($url['request_type']),
                'userID' => $url['user_id'],
                'expectedResponse' => $url['expected_response']
            ]));
        }
    }

}

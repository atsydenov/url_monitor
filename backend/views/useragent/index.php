<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel common\models\UserAgentSearch */

$this->title = 'User Agents';
$this->params['breadcrumbs'][] = $this->title;
$permissionsCreate = Yii::$app->user->can(User::PERMISSION_USER_AGENT_CREATE);
$permissionsUpdate = Yii::$app->user->can(User::PERMISSION_USER_AGENT_UPDATE);
$permissionsDelete = Yii::$app->user->can(User::PERMISSION_USER_AGENT_DELETE);
?>
<div class="user-agent-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($permissionsCreate): ?>
        <p>
            <?= Html::a('Create User Agent', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'user_agent_title',

            'user_agent',

            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'dd.MM.Y H:m:s'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'view',
                    'update' => function () {
                        return Yii::$app->user->can(User::PERMISSION_USER_AGENT_UPDATE);
                    },
                    'delete' => function () {
                        return Yii::$app->user->can(User::PERMISSION_USER_AGENT_DELETE);
                    },
                ]
            ],
        ],
    ]); ?>
</div>

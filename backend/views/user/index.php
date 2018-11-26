<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
            [
                'attribute' => 'role',
                'filter' => User::roleList(),
                'value' => function($data) {
                    $roleList = User::roleList();
                    return $roleList[$data->role];
                },
                'headerOptions' => ['width' => '105'],
            ],
            'email:email',
            [
                'attribute' => 'status',
                'filter' => User::statusList(),
                'value' => function($data) {
                    $statusList = User::statusList();
                    return $statusList[$data->status];
                },
                'headerOptions' => ['width' => '125'],
            ],
            'telegram_id',
//            [
//                'attribute' => 'created_at',
//                'format' =>  ['date', 'dd.MM.Y H:m:s'],
//            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\UserAgent;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UrlSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Urls';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Url', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'url_title',
            'url:url',
            [
                'attribute' => 'user_agent_id',
                'filter' => UserAgent::getUserAgentList(),
                'value' => function($data) {
                    $userAgentList = UserAgent::getUserAgentList();
                    return $userAgentList[$data->user_agent_id];
                },
                'headerOptions' => ['width' => '120'],
            ],
            [
                'attribute' => 'request_type',
                'filter' => ['head' => 'head', 'get' => 'get', 'post' => 'post'],
                'headerOptions' => ['width' => '100'],
            ],
            'expected_response',
            'check_interval',
            [
                'attribute' => 'active',
                'content' => function($data) {
                    if ($data->active == 1)
                    {
                        return 'Yes';
                    }
                    else
                    {
                        return 'No';
                    }
                },
                'filter' => ['1' => 'Yes', '0' => 'No'],
                'headerOptions' => ['width' => '90'],
            ],
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'dd.MM.Y H:m:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'dd.MM.Y H:m:s'],
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

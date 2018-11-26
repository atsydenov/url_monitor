<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Url */

$this->title = $model->url_title;
$this->params['breadcrumbs'][] = ['label' => 'Urls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'url_title',
            'url:url',
//            'user_agent_id',
            [
                'attribute' => 'user_agent_id',
                'value' => $userAgentName,
            ],
            'request_type',
            'expected_response',
            'check_interval',
            [
                'attribute' => 'active',
                'value' => function($model) {
                    if ($model->active == 1)
                    {
                        return 'Yes';
                    }
                    else
                    {
                        return 'No';
                    }
                }
            ],
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'd.MM.Y H:m:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'd.MM.Y H:m:s'],
            ],
        ],
    ]) ?>

</div>

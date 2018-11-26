<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\UserAgent */

$this->title = $model->user_agent_title;
$this->params['breadcrumbs'][] = ['label' => 'User Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$permissionsUpdate = Yii::$app->user->can(User::PERMISSION_USER_AGENT_UPDATE);
$permissionsDelete = Yii::$app->user->can(User::PERMISSION_USER_AGENT_DELETE);
?>
<div class="user-agent-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($permissionsUpdate): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?php endif; ?>
        <?php if ($permissionsDelete): ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user_agent_title',
            'user_agent',
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'dd.MM.Y H:m:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' =>  ['date', 'dd.MM.Y H:m:s'],
            ],
        ],
    ]) ?>

</div>

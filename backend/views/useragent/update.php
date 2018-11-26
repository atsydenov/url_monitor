<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserAgent */

$this->title = 'Update User Agent: ' . $model->user_agent_title;
$this->params['breadcrumbs'][] = ['label' => 'User Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_agent_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-agent-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

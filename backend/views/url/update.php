<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Url */

$this->title = 'Update Url: ' . $model->url_title;
$this->params['breadcrumbs'][] = ['label' => 'Urls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->url_title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="url-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'userAgentsList' => $userAgentsList,
    ]) ?>

</div>

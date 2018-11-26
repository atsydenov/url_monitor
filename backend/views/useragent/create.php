<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\UserAgent */

$this->title = 'Create User Agent';
$this->params['breadcrumbs'][] = ['label' => 'User Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-agent-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

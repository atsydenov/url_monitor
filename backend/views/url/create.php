<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Url */
/* @var $userAgentsList common\models\UserAgent */

$this->title = 'Create Url';
$this->params['breadcrumbs'][] = ['label' => 'Urls', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'userAgentsList' => $userAgentsList,
    ]) ?>

</div>

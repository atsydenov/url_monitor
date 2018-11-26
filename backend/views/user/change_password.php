<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
/* @var $user \common\models\User */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Change password for {nameAttribute}', [
    'nameAttribute' => $user->username,
]);
if (Yii::$app->controller->id == 'user')
{
    $this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['view', 'id' => $user->id]];
    $this->params['breadcrumbs'][] = 'Change password';
}
else
{
    $this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['index']];
    $this->params['breadcrumbs'][] = 'Change password';
}
?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'password_repeat')->passwordInput() ?>
            <fieldset data-role="controlgroup">
                <?= Html::submitButton('Change', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
            </fieldset>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

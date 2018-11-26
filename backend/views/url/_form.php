<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Url */
/* @var $userAgentsList common\models\UserAgent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="url-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url_title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->dropDownList([1 => 'Yes', 0 => 'No'], ['prompt'=>'']) ?>

    <?= $form->field($model, 'check_interval')->textInput() ?>

    <?= $form->field($model, 'user_agent_id')->dropDownList($userAgentsList, ['prompt'=>'']) ?>

    <?= $form->field($model, 'request_type')->dropDownList([ 'head' => 'Head', 'get' => 'Get', 'post' => 'Post'], ['prompt'=>'']) ?>

    <?= $form->field($model, 'expected_response')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

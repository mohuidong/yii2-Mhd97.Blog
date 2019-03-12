<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Manager */
/* @var $form yii\widgets\ActiveForm */
?>

<section class="content" style="background-color: #ffffff">
    <div class="manager-form">

        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'username')->textInput() ?>
        <?= $form->field($model, 'phone')->textInput() ?>
        <?= $form->field($model, 'name')->textInput() ?>
        <?= $form->field($model, 'password_hash')->input('password') ?>

        <?= $form->field($model, 'status')->dropDownList(['0' => '禁用', 10 => '活跃的']) ?>

        <div class="form-group">
            <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


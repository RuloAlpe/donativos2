<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */
/* @var $form yii\widgets\ActiveForm */
?>

<div>
    <div class="ent-usuarios-form">

        <?php $form = ActiveForm::begin(); ?>

            <div class="form-line">
                <?= $form->field($model, 'txt_username')->textInput(['maxlength' => true, "placeholder" => "Nombre"]) -> label(false) ?>
            </div>

            <div class="form-line">
                <?= $form->field($model, 'txt_apellido_paterno')->textInput(['maxlength' => true, "placeholder" => "Apellido Paterno"]) -> label(false) ?>
            </div>

            <div class="form-line">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, "placeholder" => "Email"]) -> label(false) ?>
            </div>

            <div class="form-line">
                <?= $form->field($model, 'repeatEmail')->textInput(['maxlength' => true, "placeholder" => "Repetir Email"]) -> label(false) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

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

            
                <?= $form->field($model, 'txt_username')->textInput(['maxlength' => true, "placeholder" => "Nombre"]) -> label(false) ?>
           
                <?= $form->field($model, 'txt_apellido_paterno')->textInput(['maxlength' => true, "placeholder" => "Apellido Paterno"]) -> label(false) ?>
            
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, "placeholder" => "Email"]) -> label(false) ?>
            
                <?= $form->field($model, 'repeatEmail')->textInput(['maxlength' => true, "placeholder" => "Repetir Email"]) -> label(false) ?>
           

                <input type="hidden" value="<?=$idPlan?>" name="plan" />
                <input type="hidden" name="susbcripcion" value="<?=$subscripcion?>"/>
                <input type="hidden" value="<?=$monto?>" name="monto"/>
                
                <?=Html::a("Olvidé mi contraseña", ["peticion-pass"])?>

            <div class="form-group">
                <?= Html::submitButton('<span class="ladda-label">Continuar</span>', ['class' =>'btn btn-success ladda-button', "data-style"=>"zoom-in" ]) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

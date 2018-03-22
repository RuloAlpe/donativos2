<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "";
?>
<div class="container container-full">

    <div class="registro-int">
        <h1><?= Html::encode($this->title) ?></h1>


        <div>
            <div class="ent-usuarios-form">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'username')->textInput(['autofocus' => true, "placeholder"=>"Correo eléctronico"])->label(false) ?>

                    <div class="form-group">
                       
                            <?= Html::submitButton('Recuperar contraseña', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    </div>
</div>
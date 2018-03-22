<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */

$this->title = 'Login';
$this->params['breadcrumbs'][] = ['label' => 'Ent Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container container-full">

	<div class="registro-int">

		<h2><?= Html::encode($this->title) ?></h2>
		
		<div>
			<div class="ent-usuarios-form">

				<?php 
				$form = ActiveForm::begin(); 
				?>
				
					<?= $form->field($model, 'username')->textInput(['placeholder'=>'Email'])->label(false) ?>
				
					<?= $form->field($model, 'password')->passwordInput(['placeholder'=>'Contraseña'])->label(false)?>

				<div class="form-group text-right">
					<?=Html::a("Olvidé mi contraseña", ["peticion-pass"], ['class' => 'login-link link'])?>
				</div>
				<div class="form-group">
					<?= Html::submitButton('<span class="ladda-label">Ingresar</span>', ["data-style"=>"zoom-in", 'class' => 'btn btn-primary btn-block btn-lg mt-40 ladda-button', 'name' => 'login-button'])
					?>
				</div>
				<?php ActiveForm::end(); ?>
				</div>
		</div>

    </div>

</div>
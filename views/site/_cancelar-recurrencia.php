<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<!-- Modal -->
<div id="modal-cancelar-donativo" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Motivo de suspención del donativo recurrente</h4>
      </div>
      <div class="modal-body">
      <?php $form = ActiveForm::begin([
    'id'=>'form-suspencion-donativo',
    'action'=>Url::base().'/pagos/borrar-subscripcion-cliente'
    ]); ?>

        <p>Es una pena que tengas que suspender este donativo. Eres un pilar muy importante para nosotros y nos gustaría saber el motivo por el cúal tomas esta desición </p>
        <br>
      <?=Html::hiddenInput("s", "", ["id"=>"s"])?>
      <?= $form->field($subscripcion, 'txt_motivo')->textarea(['maxlength' => true, "style"=>"resize:none"])->label(false)?>
      
      
      <div class="form-group">
        <?= Html::submitButton('<span class="ladda-label">Generar factura</span>', ['class' => 'btn btn-primary ladda-button', 'id' => 'js-btn-suspender-subscripcion', "data-style"=>"zoom-in"]) ?>
      </div>

    <?php ActiveForm::end(); ?>
    
      </div>
    </div>
  </div>
</div>
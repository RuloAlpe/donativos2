<?php

use yii\bootstrap\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<!-- Modal -->
<div id="modal-facturacion" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Datos de facturación</h4>
      </div>
      <div class="modal-body">
      <?php $form = ActiveForm::begin([
    'id'=>'form-datos-facturacion',
    'action'=>Url::base().'/pagos/generar-factura'
    ]); ?>

      <?=Html::hiddenInput("t", "", ["id"=>"transaccion"])?>
      <?= $form->field($facturacion, 'txt_rfc')->textInput(['maxlength' => true, "placeholder"=>"Ingresar RFC"])->label("Ingresar RFC") ?>
      <?= $form->field($facturacion, 'txt_nombre')->textInput(['maxlength' => true, "placeholder"=>"Nombre"])->label("Ingresar nombre") ?>
      
      <div class="form-group">
        <?= Html::submitButton('<span class="ladda-label">Generar factura</span>', ['class' => 'btn btn-primary ladda-button', 'id' => 'js-generar-factura', "data-style"=>"zoom-in"]) ?>
      </div>

    <?php ActiveForm::end(); ?>
    
      </div>
    </div>
  </div>
</div>
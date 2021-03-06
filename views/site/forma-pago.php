<?php
use yii\helpers\Url;
$this->title = "Seleccionar forma de pago";


$this->registerJsFile(
    'https://openpay.s3.amazonaws.com/openpay.v1.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
    'https://openpay.s3.amazonaws.com/openpay-data.v1.min.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
    '@web/webAssets/plugins/print-area/print-area.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
    '@web/webAssets/js/forma-pago.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>

<div class="donativos-wrapper">

  <div class="title-gral">
    <h2 class="second">
      Selecciona un medio para realizar tu contribución de $<?=$ordenCompra->num_total?> MXN
    </h2>
  </div>

  <div class="row">
      <?php 
      if(!$ordenCompra->b_subscripcion){
      ?> 
      <div class="col-xs-12 col-sm-6 col-md-3 col-md-offset-3">
        <div class="panel">
          <div class="panel-heading">
            <h3 class="panel-title">
              Establecimiento
            </h3>
          </div>
          <div class="panel-body">
                <a data-value="2" data-tarjeta="0" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
                  <img class="img-responsive" src="<?=Url::base()?>/webAssets/images/shop.png" alt="Contribución en establecimiento">
                </a>
          </div>
        </div>
      </div>
      <?php
      }?>
    
    <div class="col-xs-12 col-sm-6 <?=$ordenCompra->b_subscripcion?'col-xs-8 col-xs-offset-2 col-md-4 col-sm-offset-4 col-md-offset-4':'col-md-3'?>">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            Contribución con tarjeta
          </h3>
        </div>
        <div class="panel-body">
          <a data-value="2" data-tarjeta="1" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
                <img class="img-responsive" src="<?=Url::base()?>/webAssets/images/creditCard.png" alt="Contribución con tarjeta">
            </a>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12 text-center">
      <span class="color-white">Transacción protegida por</span>
      <img class="img-responsive img-w-250 m-auto" src="<?=Url::base()?>/webAssets/images/logo-openpay.png" alt="Open pay">
    </div>
  </div>

</div>

<?php
$this->params['modales'] = '

<div id="open-pay-ticket" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content print">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Imprimir ticket</h4>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="open-pay-card" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Contribución con tarjeta</h4>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>';
?>
<div style="display:none" class="ajax-container">
  
</div>

<div class="modal-ticket-op modal-ticket-op-hide">
</div>

<div class="modal-ticket-op-tc modal-ticket-op-hide">
</div>



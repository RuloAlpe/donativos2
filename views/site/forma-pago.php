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
    '@web/webassets/plugins/print-area/print-area.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
    '@web/webassets/js/forma-pago.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>

<h3>
  Selecciona un medio para realizar tu contribución de $<?=$ordenCompra->num_total?> MXN
</h3>

<div class="row">
    <?php 
    if(!$ordenCompra->b_subscripcion){
    ?> 
    <div class="col-md-3 col-md-offset-3">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            Establecimiento
          </h3>
        </div>
        <div class="panel-body">
              <a data-value="2" data-tarjeta="0" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
                <img class="img-responsive" src="<?=Url::base()?>/webassets/images/shop.png" alt="Pago en establecimiento">
              </a>
        </div>
      </div>
    </div>
    <?php
    }?>

  <div class="col-md-3">
    <div class="panel">
      <div class="panel-heading">
        <h3 class="panel-title">
          Pago con tarjeta
        </h3>
      </div>
      <div class="panel-body">
        <a data-value="2" data-tarjeta="1" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
              <img class="img-responsive" src="<?=Url::base()?>/webassets/images/creditCard.png" alt="Pago con tarjeta">
          </a>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 text-center">
    <span>Transacción protegida por</span>
    <img class="img-responsive" src="<?=Url::base()?>/webassets/images/logo-openpay.png" alt="Open pay">
  </div>
</div>
          
<!-- Modal -->
<div id="open-pay-ticket" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        
      </div>
    </div>
  </div>
</div>

<div style="display:none" class="ajax-container">
  
</div>

<div class="modal-ticket-op modal-ticket-op-hide">
</div>

<div class="modal-ticket-op-tc modal-ticket-op-hide">
</div>



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


  <div class="container container-full">
    <!-- container-column -->
    <div class="tipos-de-pago-content">
      
      <h3>
        Seleciona un medio para realizar tu contribución
      </h3>
      <div class="tipos-de-pago">
        <div class="pagocard">

          <?php 
          if(true || !$ordenCompra->b_subscripcion){
          ?> 
          <div>
            <a data-value="2" data-tarjeta="0" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
              <img src="<?=Url::base()?>/webassets/images/shop.png" alt="Pago en establecimiento">
            </a>
            <span class="caption">
              Establecimiento
            </span>
          </div>
          <?php
          }
          ?>

          <div>  
            <a data-value="2" data-tarjeta="1" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
              <img src="<?=Url::base()?>/webassets/images/creditCard.png" alt="Pago con tarjeta">
            </a>
            <span class="caption">
              Tarjeta de Crédito
            </span>
          </div>  
        </div>
      </div>

      <div class="open-pay">
        <span>Transacción protegida por</span>
        <img src="<?=Url::base()?>/webassets/images/logo-openpay.png" alt="Open pay">
      </div>

    </div>
  </div>
  


<div style="display:none" class="ajax-container">
  
</div>

<div class="modal-ticket-op modal-ticket-op-hide">
</div>

<div class="modal-ticket-op-tc modal-ticket-op-hide">
</div>



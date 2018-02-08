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


  <div class="container container-column container-full">
    <div class="tipos-de-pago-content">
      <div class="open-pay">
        <img src="<?=Url::base()?>/webassets/images/logo-openpay.png" alt="Open pay">
      </div>
      <h3>Selecciona una forma de pago</h3>
      <div class="tipos-de-pago">
        <div class="pagocard">
          <div>
            <a data-value="2" data-tarjeta="0" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
              <img src="<?=Url::base()?>/webassets/images/shop.png" alt="Pago en establecimiento">
            </a>
            <span class="caption">
              Pago en establecimiento
            </span>
          </div>
          <div>  
            <a data-value="2" data-tarjeta="1" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago">
              <img src="<?=Url::base()?>/webassets/images/creditCard.png" alt="Pago con tarjeta">
            </a>
            <span class="caption">
              Pago con tarjeta
            </span>
          </div>  
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



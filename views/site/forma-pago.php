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

<section class="donativos-wrapper">
<a href="<?=Url::base()?>/site/mis-boletos" class="btn btn-success btn-boletos">Mis Boletos</a>
  <div class="container container-column container-full">
    <div class="tipos-de-pago-content">
      <h3>Selecciona un tipo de pago</h3>
      <div class="tipos-de-pago">
        <div class="pagocard">
            <a data-value="1" data-tokenoc="<?=$tokenOc?>" data-token="tp_80244ff4f23c1f06e8262c2b0a7462a6571112ad791dc" class="btn-tipo-de-pago js-btn-pago"><img src="<?=Url::base()?>/webassets/images/logo-paypal.png" alt=""></a><span class="caption">Tarjeta de Crédito o cuenta</span>
        </div>
        <div class="pagocard">
          <a data-value="2" data-tokenoc="<?=$tokenOc?>" data-token="tp_3922b05cccd499fb9d2c415038ab9c08571112b938d1d" class="btn-tipo-de-pago js-btn-pago"><img src="<?=Url::base()?>/webassets/images/logo-openpay.png" alt=""></a><span class="caption">Pago en establecimiento</span>
        </div>
      </div>
    </div>
  </div>
  <footer><a class="sponsor" href="http://www.2geeksonemonkey.com">Desarrollo donado por 2 Geeks one Monkey</a></footer>
</section>


<div style="display:none" class="ajax-container">
  
</div>

<div class="modal-ticket-op modal-ticket-op-hide">
</div>

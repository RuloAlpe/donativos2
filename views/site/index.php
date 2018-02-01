<?php
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = 'Elegir monto a donar';
$this->registerJsFile(
    '@web/webassets/js/index.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>
<section class="donativos-wrapper">
<a href="<?=Url::base()?>/site/mis-boletos" class="btn btn-success btn-boletos">Mis donaciones</a>
    <div class="container container-full">
      <div class="donativos-content">
        <h3 class="title">Elige el monto con el cual desees colaborar</h3>
        <div class="tarjetas-wrapper">
          <div class="tarjeta">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <span class="cantidad">100</span>
              <span class="moneda">mxn</span></div>
            <a href="<?= Url::base()?>/sign-up?monto=100" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="100">Realizar Donativo</a>
          </div>
          <div class="tarjeta">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <span class="cantidad">200</span>
              <span class="moneda">mxn</span></div>
            <a href="<?= Url::base()?>/sign-up?monto=200" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="200">Realizar Donativo</a>
          </div>
          <div class="tarjeta">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <span class="cantidad">500</span>
              <span class="moneda">mxn</span></div>
            <a href="<?= Url::base()?>/sign-up?monto=500"  class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="500">Realizar Donativo</a>
          </div>
          <div class="tarjeta">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <span class="cantidad">900</span>
              <span class="moneda">mxn</span></div>
            <a href="<?= Url::base()?>/sign-up?monto=900" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="900">Realizar Donativo</a>
          </div>
        </div>
        <div class="custom-amount-wrapper">
          <h3>¿ Tienes otro número en mente ?</h3>
          <div class="custom-bar">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <input class="js-add ingreso_monto" type="text" placeholder="100.00">
              <span class="moneda">mxn</span>
            </div>
            <a class="btn btn-default btn_nuevo_monto">Realizar Donativo</a>
          </div>

        </div>
      </div>
    </div>
    <footer class="not-absolute"><a class="sponsor" href="http://www.2geeksonemonkey.com">Desarrollo por 2 Geeks one Monkey</a></footer>
  </section> 
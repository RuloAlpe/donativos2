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

    <div class="container container-full">
      <div class="donativos-content">
        <h3 class="title">Elige el monto con el cual desees colaborar</h3>
        <div class="tarjetas-wrapper">
          <div class="tarjeta">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <span class="cantidad">250</span>
              <span class="moneda">mxn</span></div>
              <?php if (Yii::$app->user->isGuest) { ?>
                <a href="<?= Url::base()?>/sign-up?monto=250" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="250">Realizar Donativo</a>
              <?php }else{ ?>
                <a href="<?= Url::base()?>/site/guardar-orden?monto=250" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="250">Realizar Donativo</a>
              <?php } ?>                
          </div>
          <div class="tarjeta">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <span class="cantidad">500</span>
              <span class="moneda">mxn</span></div>
              <?php if (Yii::$app->user->isGuest) { ?>
                <a href="<?= Url::base()?>/sign-up?monto=500" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="500">Realizar Donativo</a>
              <?php }else{ ?>
                <a href="<?= Url::base()?>/site/guardar-orden?monto=500" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="500">Realizar Donativo</a>
              <?php } ?>
          </div>
          <div class="tarjeta">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <span class="cantidad">1000</span>
              <span class="moneda">mxn</span></div>
              <?php if (Yii::$app->user->isGuest) { ?>
                <a href="<?= Url::base()?>/sign-up?monto=1000" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="1000">Realizar Donativo</a>
              <?php }else{ ?>
                <a href="<?= Url::base()?>/site/guardar-orden?monto=1000" class="btn btn-default btn-donativo js-select-amount btn-success"  data-value="1000">Realizar Donativo</a>
              <?php } ?>
          </div>
          
        </div>
        <div class="custom-amount-wrapper">
          <h3>¿ Tienes otro número en mente ?</h3>
          <div class="custom-bar">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              <?php if (Yii::$app->user->isGuest) { ?>
                <input data-log="0" class="js-add ingreso_monto input-monto" type="text" placeholder="100.00">
              <?php }else{ ?>
                <input data-log="1" class="js-add ingreso_monto input-monto" type="text" placeholder="100.00">                
              <?php } ?>
              <span class="moneda">mxn</span>
            </div>
            <a class="btn btn-default btn_nuevo_monto">Realizar Donativo</a>
          </div>

        </div>
      </div>
    </div>
    
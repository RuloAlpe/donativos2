 
<?php
use yii\helpers\Url;
use app\models\Calendario;
$this->registerJsFile(
    '@web/webassets/js/open-pay.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$date = date($ordenCompra->fch_creacion);
$date = strtotime(date("Y-m-d", strtotime($date)) . " +1 month");
$date = date("Y-m-d",$date);

?>

<div class="cargo-total">
      <h5>Total a Pagar</h5>
      <span class="monto-total">$  <?=number_format($charger->amount)?></span>
      <span class="moneda">mxn</span>
      <span class="comision">+ 8 pesos de comisión</span>
    </div>

    <div class="barcode">
      <h3>Para completar tu pago presenta este código de barras en cualquier establecimiento participante</h3>
      <!--<h4>Este ticket será vigente hasta el:</h4>

      <span class="fecha">31/Dic/18</span>-->

      <img src="<?=$charger->payment_method->barcode_url?>" alt="Codigo de Barras">
      <div class="num-ref"><?=$charger->payment_method->reference?></div>
      <span class="caption">En caso de que el escáner no sea capaz de leer el código de barras, escribir la referencia tal como se muestra.</span>
    </div>

    <div class="instrucciones-cajero">
      <h4>Instrucciones para el cajero</h4>
      <ol>
        <li>Ingresar al menú de Pago de Servicios</li>
        <li>Seleccionar Paynet</li>
        <li>Escanear el código de barras o ingresar el núm. de referencia</li>
        <li>Ingresa la cantidad total a pagar</li>
        <li>Cobrar al cliente el monto total más la comisión de $8 pesos</li>
        <li>Confirmar la transacción y entregar el ticket al cliente</li>
      </ol>
    </div>
    <span class="caption">Para cualquier duda sobre como cobrar, por favor llamar al teléfono +52 (55) 5351 7371 en un horario de 8am a 9pm de lunes a domingo</span>
    <div class="tiendas-participantes">
      <img src="<?=Url::base()?>/webassets/images/tiendas.jpg" alt="Tiendas participantes">
    </div>
    <div class="powered">
      <span>powered by</span><img src="<?=Url::base()?>/webassets/images/logo-openpay.png" alt="powered by openpay">
    </div>
    <a class="close-modal"><i class="ion ion-close"></i></a>
    <a href="" class="btn btn-primary print-btn">Imprimir este ticket</a>

                                    
                                                                

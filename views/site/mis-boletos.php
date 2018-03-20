<?php
use yii\helpers\Url;
use app\models\Calendario;
use app\models\EntSubscripciones;
use app\models\EntDatosFacturacion;
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
$this->title = "Mis donaciones";

$this->params['btns'] = '';

$this->registerJsFile(
  '@web/webAssets/js/donaciones.js',
  ['depends' => [\app\assets\AppAsset::className()]]
);
?>



<!-- <a href="<?=Url::base()?>" class="btn btn-success btn-boletos btn-inicio">Inicio</a> -->
    
<div class="container container-full">
  <div class="mis-donaciones-content">

    <div class="title-gral">
      <h2 class="second">
        Mis donaciones
      </h2>
    </div>

    <div class="donaciones-table">
     
      <div class="donaciones-accordion-head">
        <div class="donaciones-accordion__list">
          <div class="header">
            <p class="donaciones-tipo">Tipo</p>
            <p class="donaciones-monto">Monto</p>
            <p class="donaciones-fecha">Fecha</p>
            <p class="donaciones-facturar">Facturar</p>
            <p class="donaciones-recurrencia">Cancelar recurrencia</p>
          </div>
        </div>
      </div>

      <ul class="donaciones-accordion" id="accordion">
        
        <?php
        foreach($boletos as $donacion){
          $json = json_decode($donacion->txt_cadena_comprador);

          // Se revisa si el pago recibido es un pago recurrente
          $recurrencia = "";
          $cancelarRecurrencia = "";
          if (isset($json->transaction->subscription_id)) {
            
            $recurrencia = "Donativo recurrente";
            $subscripcion = EntSubscripciones::find()->where(["txt_subscipcion_open_pay"=>$json->transaction->subscription_id])->one();

            if($subscripcion->b_subscrito){
              $cancelarRecurrencia = '<button id="cancelar-'.$json->transaction->subscription_id.'" class="btn donaciones-btn-cancelar-donativo js-cancelar-subscripcion" data-token="'.$json->transaction->subscription_id.'">Cancelar</button>';
            }else{

              $cancelarRecurrencia = "<span class='donaciones-recurrencia-cancelada'>Cancelada</span>";
            }

            
          }

          // Se revisa si el pago ya ha sido facturado
          if(!$donacion->b_facturado){
            $btnGenerarFactura = '<button class="btn donaciones-btn-facturar js-generar-factura" id="facturar-'.$donacion->txt_transaccion.'" data-token="'.$donacion->txt_transaccion.'">Facturar</button>';
            // $btnGenerarFactura = '<a class="btn donaciones-facturar-pdf">PDF</a> <a class="btn donaciones-facturar-xml">XML</a>';
          }else{
            $btnGenerarFactura = '<a class="btn donaciones-facturar-pdf js-descargar-pdf" target="_blank" href='.Url::base().'/pagos/descargar-factura-pdf?token='.$donacion->txt_transaccion.'>PDF</a> 
            <a href='.Url::base().'/pagos/descargar-factura-xml?token='.$donacion->txt_transaccion.' target="_blank" class="btn donaciones-facturar-xml js-descargar-xml">XML</a>';
          }
        ?>
        <li class="donaciones-accordion__list">
          <div class="link">
            <p class="donaciones-tipo"><?=$recurrencia?$recurrencia: "Pago único"?></p>
            <p class="donaciones-monto">$<?=number_format((float)$donacion->txt_monto_pago, 2, '.', ','); ?></p>
            <p class="donaciones-fecha"><?=Calendario::getDateComplete($donacion->fch_pago)?></p>

            <p class="donaciones-facturar botones-<?=$donacion->txt_transaccion?>">

              <?=$btnGenerarFactura?>
            </p>
            <p class="donaciones-recurrencia"><?=$cancelarRecurrencia?></p>
            <span class="link__title"></span>
            <span class="glyphicon glyphicon-chevron-down"></span>
          </div>
          <div class="donaciones-submenu">
            <div class="donaciones-submenu-small">
              <span>Id de transacción:</span> <p><?=$donacion->txt_transaccion?></p>
            </div>
            <div class="donaciones-submenu-panel">
              <span>Id de transacción:</span> <p><?=$donacion->txt_transaccion?></p>
            </div>
            <div class="donaciones-submenu-panel">
              <span>Tipo:</span> <p><?=$recurrencia?$recurrencia: "Pago único"?></p>
            </div>
            <div class="donaciones-submenu-panel-row">
              <div class="donaciones-submenu-panel-col">
                <span>Facturar:</span> <p class="botones-<?=$donacion->txt_transaccion?>"><?=$btnGenerarFactura?></p>
              </div>
              <div class="donaciones-submenu-panel-col">
                <span>Cancelar recurrencia:</span> <p><?=$cancelarRecurrencia?></p>
              </div>
            </div>
          </div>
        </li>
        <?php
        }
        ?>
       
      </ul>

    </div>

  </div>
</div>


<?php 
// Datos de facturación
$usuario = Yii::$app->user->identity;
$facturacion = EntDatosFacturacion::find()->where(["id_usuairo"=>$usuario->id_usuario])->one();

if(!$facturacion){
 $facturacion = new EntDatosFacturacion();
 $facturacion->txt_nombre = $usuario->nombreCompleto;
}
?>

<?php

$this->params['modales'] = $this->render("_facturacion", ['facturacion'=>$facturacion]);





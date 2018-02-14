<?php
use yii\helpers\Url;

$this->params['btns'] = '';

$this->registerJsFile(
  '@web/webAssets/js/donaciones.js',
  ['depends' => [\app\assets\AppAsset::className()]]
);
?>


<a href="<?=Url::base()?>" class="btn btn-success btn-boletos btn-inicio">Inicio</a>
    
<div class="container container-full">
  <div class="mis-donaciones-content">
    <h3>Mis donaciones</h3>

    <div class="donaciones-table">
      
      <div class="donaciones-head">
        <p class="donaciones-monto">Monto de donativo</p>
        <p class="donaciones-transaccion">ID de transacción</p>
        <p class="donaciones-fecha">Fecha de la donación</p>  
      </div>

      <?php
      foreach($boletos as $boleto){
        // $json = json_decode($boleto->txt_cadena_comprador);
        // if($json->subscription_id){
        //   echo "Subscripción";
        // }
      ?>
      <div class="donaciones-row">
        <p class="donaciones-monto">$<?=number_format((float)$boleto->txt_monto_pago, 2, '.', ''); ?></p>
        <p class="donaciones-transaccion"><?=$boleto->txt_transaccion?></p>
        <p class="donaciones-fecha"><?=$boleto->fch_pago?></p>  

        </div>
      <?php
      }
      ?>

    </div>


  <ul class="donaciones-accordion" id="accordion">

    <li class="donaciones-accordion__list">
      <div class="link">
        <p class="donaciones-datos">Datosa</p>
        <p class="donaciones-monto">Monto</p>
        <p class="donaciones-fecha">Fecha</p>
        <p class="donaciones-tipo">Tipo</p>
        <p class="donaciones-facturar">Facturar</p>
        <p class="donaciones-recurrencia">Recurrencia</p>
        <span class="link__title"></span><i class="ion ion-ios-arrow-down"></i>
      </div>
      <ul class="submenu">
        <span>Listo</span>
      </ul>
    </li>

    <li class="donaciones-accordion__list">
      <div class="link">
        <p class="donaciones-datos">Datosa</p>
        <p class="donaciones-monto">Monto</p>
        <p class="donaciones-fecha">Fecha</p>
        <p class="donaciones-tipo">Tipo</p>
        <p class="donaciones-facturar">Facturar</p>
        <p class="donaciones-recurrencia">Recurrencia</p>
        <span class="link__title"></span><i class="ion ion-ios-arrow-down"></i>
      </div>
      <ul class="submenu">
        <span>Listo</span>
      </ul>
    </li>

    <li class="donaciones-accordion__list">
      <div class="link">
        <p class="donaciones-datos">Datosa</p>
        <p class="donaciones-monto">Monto</p>
        <p class="donaciones-fecha">Fecha</p>
        <p class="donaciones-tipo">Tipo</p>
        <p class="donaciones-facturar">Facturar</p>
        <p class="donaciones-recurrencia">Recurrencia</p>
        <span class="link__title"></span><i class="ion ion-ios-arrow-down"></i>
      </div>
      <ul class="submenu">
        <span>Listo</span>
      </ul>
    </li>
   
  </ul>



  </div>
</div>



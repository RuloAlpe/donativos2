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
      
     
      

      <div class="donaciones-accordion-head">
        <div class="donaciones-accordion__list">
          <div class="header">
            <p class="donaciones-datos">Datos</p>
            <p class="donaciones-monto">Monto</p>
            <p class="donaciones-fecha">Fecha</p>
            <p class="donaciones-tipo">Tipo</p>
            <p class="donaciones-facturar">Facturar</p>
            <p class="donaciones-recurrencia">Recurrencia</p>
          </div>
        </div>
      </div>

      <ul class="donaciones-accordion" id="accordion">
        
        <?php
        foreach($boletos as $boleto){
          // $json = json_decode($boleto->txt_cadena_comprador);
          // if($json->subscription_id){
          //   echo "Subscripción";
          // }
        ?>
        <li class="donaciones-accordion__list">
          <div class="link">
            <p class="donaciones-datos">Datos</p>
            <p class="donaciones-monto">$<?=number_format((float)$boleto->txt_monto_pago, 2, '.', ''); ?></p>
            <p class="donaciones-fecha"><?=$boleto->fch_pago?></p>
            <p class="donaciones-tipo">Tipo</p>
            <p class="donaciones-facturar">Facturar</p>
            <p class="donaciones-recurrencia">Recurrencia</p>
            <span class="link__title"></span><i class="ion ion-ios-arrow-down"></i>
          </div>
          <ul class="submenu">
            <span>ID: <?=$boleto->txt_transaccion?></span>
          </ul>
        </li>
        <?php
        }
        ?>
       
      </ul>

    </div>

  </div>
</div>



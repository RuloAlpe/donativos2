<?php
use yii\helpers\Url;

$this->params['btns'] = '';
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

  </div>
</div>
    





               
               


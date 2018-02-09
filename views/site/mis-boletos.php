<?php
use yii\helpers\Url;

$this->params['btns'] = '<a href="'.Url::base().'" class="btn btn-success btn-boletos">Inicio</a>';
?>


<a href="<?=Url::base()?>" class="btn btn-success btn-boletos btn-inicio">Inicio</a>
    
<div class="container container-full">
  <div class="mis-donaciones-content">
    <h3>Mis donaciones</h3>

    <div class="donaciones-table">
      
      <div class="donaciones-head">
        <p class="donaciones-monto">Monto</p>
        <p class="donaciones-transaccion">Transacción</p>
        <p class="donaciones-fecha">Fecha del pago</p>  
      </div>

      <?php
      foreach($boletos as $boleto){
      ?>
      <div class="donaciones-row">
        <p class="donaciones-monto">$<?=$boleto->txt_monto_pago?>.00</p>
        <p class="donaciones-transaccion"><?=$boleto->txt_transaccion?></p>
        <p class="donaciones-fecha"><?=$boleto->fch_pago?></p>  
        </div>
      <?php
      }
      ?>

    </div>

  </div>
</div>
    





               
               


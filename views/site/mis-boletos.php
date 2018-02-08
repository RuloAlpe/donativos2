<?php
use yii\helpers\Url;
?>
<section class="donativos-wrapper">
<a href="<?=Url::base()?>" class="btn btn-success btn-boletos">Inicio</a>
    <div class="container container-column container-full">
      <div class="boletos-content">
        <h3>Estos son las donaciones</h3>
        <?php
        foreach($boletos as $boleto){
        ?>
        <div class="boletos">
          <div class="boleto">
            <h2>Monto: <?=$boleto->txt_monto_pago?></h2>
            <h4>Transaccion: <?=$boleto->txt_transaccion?></h4>
            <h4>Fecha del pago: <?=$boleto->fch_pago?></h4>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
    </div>
    <footer class="not-absolute"><a class="sponsor" href="http://www.2geeksonemonkey.com">Desarrollo por 2 Geeks one Monkey</a></footer>
</section>





               
               


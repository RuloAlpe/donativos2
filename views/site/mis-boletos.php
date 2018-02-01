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
            <h2> <?=$boleto->txt_codigo?></h2>
          </div>
        </div>
        <?php
        }
        ?>
      </div>
    </div>
    <footer class="not-absolute"><a class="sponsor" href="http://www.2geeksonemonkey.com">Desarrollo por 2 Geeks one Monkey</a></footer>
</section>





               
               


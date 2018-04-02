<?php
use yii\helpers\Url;
use app\config\Config;


$this->registerJsFile(
    '@web/webassets/js/geeks.js',
    ['depends' => [\app\assets\AppAsset::className()]]
);
?>

<!-- <a href="<?=Url::base()?>" class="btn btn-success btn-boletos">Inicio</a> -->
<div class="container container-full container-gracias">
	<div class="boletos-content">
		
		<div class="boletos-int">
			<i class="ion ion-ios-heart"></i>
			<h2>Gracias por tu <span>donación</span></h2>
			<h5>
			“Gracias por su donación, para poder generar tu recibo deducible da clic en ver mis donativos y posteriormente en el botón generar recibo. Cualquier duda escribenos a comunicacion@figma.org.mx”
			</h5>
			<a class="btn btn-success" href="<?=Config::LIGA_INICIAL?>">
				Ir al inicio
			</a>
		</div>

	</div>
</div>
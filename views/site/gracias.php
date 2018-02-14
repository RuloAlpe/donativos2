<?php
use yii\helpers\Url;


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
				
			</h5>
			<a class="btn boletos-int-btn" href="https://www.figma.org.mx/">
				Ir al inicio
			</a>
		</div>

	</div>
</div>
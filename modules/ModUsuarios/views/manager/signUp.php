<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\EntUsuarios */

$this->title = 'Antes de realizar tu donativo necesitamos nos compartas unos datos';
$this->params['breadcrumbs'][] = ['label' => 'Ent Usuarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(
    '@web/webAssets/js/signup.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>
<div class="container container-full">

	<div class="registro-int">

	    <h2><?= Html::encode($this->title) ?></h2>

	    <?= $this->render('_form', [
	        'model' => $model,
	        'idPlan' =>$idPlan,
					'subscripcion'=>$subscripcion,
					'monto'=>$monto
	    ]) ?>

    </div>

</div>
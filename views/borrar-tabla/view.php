<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BorrarTabla */
?>
<div class="borrar-tabla-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_borrar_tabla',
            'txt_nombre',
            'txt_descripcion',
            'b_habilitado',
        ],
    ]) ?>

</div>

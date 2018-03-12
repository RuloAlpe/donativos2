<?php
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this yii\web\View */
$this->title = 'Elegir monto a donar';
$this->registerJsFile(
    '@web/webassets/js/index.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
  'https://code.jquery.com/ui/1.10.4/jquery-ui.min.js',
  ['depends' => [\yii\web\JqueryAsset::className()]]
);

if (Yii::$app->user->isGuest) { 
  $url = "//sign-up";
}else{
  $url = "//site/guardar-orden";
} 
?>

<?= Html::beginForm([$url], 'post') ?>

<div class="row js-tipo-donativo">
  <div class="col-md-6">
    <div class="panel card" for="test">
      <div class="panel-body text-center">
        <h2>Donativo único </h2>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <label class="panel card">
      <div class="panel-body text-center">
        <h2>Apadrinar un niño</h2>
        <input id="js_susbcripcion" type="checkbox" name="susbcripcion" class="checkbox" value="1"/>
      </div>
    </label>
  </div>
</div>

<div class="js-slider" style="display:none">
  <h3>Elige una cantidad</h3>
  
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h3 class="js-apadrinar" style="display:none">Apadrinare a un niño y</h3>
      <h3>
        Mi donativo será de $ <span class="js-amount">10</span><small>.00</small>
      </h3>
    </div>
  </div>

  <div class="row p">
    <div class="col-md-6 col-md-offset-3">
      <div id="slider"></div>
      <?=Html::hiddenInput('plan', 10, ['id'=>'amount_plan'])?>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <button class="btn btn-warning js-back">
        Seleccionar tipo de donativo
      </button>
      <?=Html::submitButton("Realizar donativo", ["class"=>"btn btn-success"])?>
    </div>
  </div>
  
</div>  
<?= Html::endForm() ?>
<?php
$this->registerJs("
  $(document).ready(function(){

    $('#slider').slider({
      animate: true,
      value:1,
      min: 10,
      max: 9999,
      step: 10,
      slide: function(event, ui) {
        update(1,ui.value);
      }
    });

    update(1, 10);
  });
  ",
  View::POS_READY,
  'my-button-handler'
);
?>

          
       
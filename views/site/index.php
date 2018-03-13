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
  '//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
  ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerJsFile(
  '//cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js',
  ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->params['classBody'] = true;

if (Yii::$app->user->isGuest) { 
  $url = "//sign-up";
}else{
  $url = "//site/guardar-orden";
} 
?>

<?= Html::beginForm([$url], 'post') ?>

  <div class="page-donativo">

    <!-- <div class="title-gral">
      <h3>
        <span class="primary">Tu puedes hacer la diferencia</span>
        <span class="second">Elige el monto con el cual deseas contribuir</span>
      </h3>
    </div> -->

    <div class="row js-tipo-donativo">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="panel card" for="test">
          <div class="panel-body text-center">
            <h2>Donativo único </h2>
            <div class="panel-bg"></div>
          </div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <label class="panel card">
          <div class="panel-body text-center">
            <h2>Apadrinar un niño</h2>
            <input id="js_susbcripcion" type="checkbox" name="susbcripcion" class="checkbox" value="1"/>
            <div class="panel-bg"></div>
          </div>
        </label>
      </div>
    </div>

    <div class="js-slider" style="display:none">

      <div class="title-gral">
        <h3 class="primary">Elige una cantidad</h3>
      
        <div class="row">
          <div class="col-md-6 col-md-offset-3">
            <h3 class="second js-apadrinar" style="display:none">Apadrinare a un niño y</h3>
            <h3 class="tertiary">
              Mi donativo será de 
              <div class="diner">
                $ <span class="donar-costo js-amount">10</span><small>.00</small>
              </div>
            </h3>
          </div>
        
        </div>
      </div>

      <div class="row p">
        <div class="col-md-6 col-md-offset-3">
          <div class="donativo-slider" id="slider"></div>
          <?=Html::hiddenInput('plan', 10, ['id'=>'amount_plan'])?>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6 col-md-offset-3 text-center">
          <?=Html::submitButton("<span class='ladda-label'></span>Realizar donativo", ["class"=>"btn btn-success btn-lg ladda-button", "data-style"=>"zoom-in"])?>
          <button class="btn btn-warning btn-outline js-back">
            Cambiar mi tipo de donación
          </button>
        </div>
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

          
       
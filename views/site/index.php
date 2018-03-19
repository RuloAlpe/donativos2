<?php
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use app\assets\AppAsset;

/* @var $this yii\web\View */
$this->title = 'Elegir monto a donar';

$this->registerJsFile(
    '@web/webAssets/js/index.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$this->registerCssFile(
  '@web/webAssets/plugins/nouislider/nouislider.css',
  ['depends' => [AppAsset::className()]]
);

$this->registerJsFile(
  '@web/webAssets/plugins/nouislider/nouislider.js',
  ['depends' => [AppAsset::className()]]
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
            <h2>Apadrinar una mamá soltera</h2>
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
            <h3 class="second js-apadrinar" style="display:none">Apadrinaré a una mamá soltera</h3>
            <h3 class="tertiary">
              Mi donativo será de 
              <div class="diner">
                MXN $ <span class="donar-costo js-amount">250</span>
              </div>
            </h3>
          </div>
        
        </div>
      </div>

      <div class="row p">
        <div class="col-md-6 col-md-offset-3">
          <div class="donativo-slider" id="slider"></div>
          <?=Html::hiddenInput('plan', 250, ['id'=>'amount_plan'])?>
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

    var bigValueSlider = document.getElementById('slider');
    
  
    noUiSlider.create(bigValueSlider, {
      start: 250,
      step: 50,
      range: {
        min: 250,
        max: 9999
      }
    });



    bigValueSlider.noUiSlider.on('update', function ( values, handle ) {
      update(1, values);
    });

    
  });
  ",
  View::POS_READY,
  'my-button-handler'
);
?>

          
       
<?php
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
$this->title = 'Elegir monto a donar';
$this->registerJsFile(
    '@web/webassets/js/index.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
?>

    <div class="container container-full">
      <div class="donativos-content">
        <h3 class="title">
          <span class="title-lg">Tu puedes hacer la diferencia</span>
          <span class="title-xs">Elige el monto con el cual deseas contribuir</span>  
        </h3>
        <div class="tarjetas-wrapper">

        <?php
        foreach($planes as $plan){
        ?>
          <?php
          if (Yii::$app->user->isGuest) { 
            $url = "//sign-up";
          }else{
            $url = "//site/guardar-orden";
          } 
          ?>
          

          <div class="tarjeta">

            <?= Html::beginForm([$url], 'post') ?>

            <div class="tarjeta-int">
              
              <div class="header">Donar</div>
              <div class="monto">
                <span class="currency">$</span>
                <span class="cantidad"><?=$plan->num_cantidad?></span>
                <span class="moneda">mxn</span></div>

                <input type="hidden" value="<?=$plan->id_plan?>" name="plan" />
                
                <input type="hidden" value="<?=$plan->num_cantidad?>" name="monto"/>
                <button type="submit" class="btn btn-default btn-donativo js-select-amount btn-success" data-style="zoom-in" data-value="500">
                  <span class="ladda-label">Realizar Donativo</span>
                </button>
                
            </div>

            <div class="check">
              <div class="check__item">
                <label class="label--checkbox">
                  <input type="checkbox" name="susbcripcion" class="checkbox" value="1"/>
                  Contribuir mensualmente
                </label>
              </div>
            </div>

            <?= Html::endForm() ?>

          </div>
          

        <?php
        }
        ?>

          
        </div>
          <?php
          if (Yii::$app->user->isGuest) { 
            $url = "//sign-up";
          }else{
            $url = "//site/guardar-orden";
          } 
          ?>

          

        <div class="custom-amount-wrapper">
        <?= Html::beginForm([$url], 'post') ?>
          <h3>¿ Tienes otro número en mente ?</h3>
          <div class="custom-bar">
            <div class="header">Donar</div>
            <div class="monto">
              <span class="currency">$</span>
              
              <?=Html::dropDownList("plan", 6, ArrayHelper::map($planesExtras, 'id_plan', 'num_cantidad') , ["class"=>" ingreso_monto input-monto"])?>

              <span class="moneda">mxn</span>
            </div>
            <button type="submit" class="btn btn-default btn_nuevo_monto js-select-amount" data-style="zoom-in" data-value="500">
                  <span class="ladda-label">Realizar Donativo</span>
                </button>
            <!-- <a class="btn btn-default btn_nuevo_monto">Realizar Donativo</a> -->
          </div>
          <div class="check">
              <div class="check__item">
                <label class="label--checkbox">
                  <input type="checkbox" name="susbcripcion" class="checkbox" value="1"/>
                  Contribuir mensualmente
                </label>
              </div>
            </div>
          <?= Html::endForm() ?>
        </div>

        

      </div>
    </div>
    
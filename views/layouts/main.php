<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script>
        var baseUrl = "<?=Yii::$app->urlManager->createAbsoluteUrl ( [''] )?>";
    </script>
    <?php $this->head() ?>
</head>
<body >
<?php $this->beginBody() ?>
    <section class="donativos-wrapper">
        <?php if(!Yii::$app->user->isGuest){ ?>
            <?=isset($this->params["btns"])?$this->params["btns"]:'<a href="'.Url::base().'/site/mis-donaciones" class="btn btn-success btn-boletos">Ver mis donativos</a>'?>
            
            
        <?php }else{
        ?>
            <a href="<?=Url::base()?>/login" class="btn btn-success btn-boletos">Iniciar sesión</a>
        <?php
        } ?>
        <?= $content ?>
        
        <footer>
      <a class="sponsor" href="https://www.figma.org.mx/aviso-privacidad.html">Aviso de privacidad</a>
  
    </footer>
    </section> 
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

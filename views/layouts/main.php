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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="57x57" href="<?Url::base()?>/webassets/favicons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="<?Url::base()?>/webassets/favicons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="<?Url::base()?>/webassets/favicons/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="<?Url::base()?>/webassets/favicons/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="<?Url::base()?>/webassets/favicons/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="<?Url::base()?>/webassets/favicons/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="<?Url::base()?>/webassets/favicons/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?Url::base()?>/webassets/favicons/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?Url::base()?>/webassets/favicons/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="<?Url::base()?>/webassets/favicons/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?Url::base()?>/webassets/favicons/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="<?Url::base()?>/webassets/favicons/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?Url::base()?>/webassets/favicons/favicon-16x16.png">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script>
        var baseUrl = "<?=Yii::$app->urlManager->createAbsoluteUrl ( [''] )?>";
    </script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-116139902-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-116139902-1');
</script>
    <?php $this->head() ?>
</head>
<body class="bkgd-gral">

<?=isset($this->params['classBody'])?'<div class="bkgd-gral-mask"></div>':''?> 



<?php $this->beginBody() ?>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <!-- <div class="navbar-header">
                    <a class="navbar-brand" href="#">WebSiteName</a>
                </div> -->
                <ul class="nav navbar-nav">
                    <li class="active"><a class="btn btn-gral" href="https://www.figma.org.mx/donativos/">Home</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php if(!Yii::$app->user->isGuest){ ?>
                        <li><?=isset($this->params["btns"])?$this->params["btns"]:'<a href="'.Url::base().'/site/mis-donaciones" class="btn btn-success">Ver mis donativos</a>'?></li>
                        <li><a href="<?=Url::base()?>/site/logout" class="btn btn-logout"><span class="glyphicon glyphicon-log-out"></span>Salir</a></li>
                    <?php }else{
                    ?>
                        <li><a href="<?=Url::base()?>/login" class="btn btn-gral">Ingresar</a></li>
                    <?php
                    } ?>
                   
                </ul>
            </div>    
        </div>
    </nav>

    <div class="container page">
        <?= $content ?>
    </div>
    
    
    <footer class="site-footer">
      <a class="sponsor" target="_blank" href="https://www.figma.org.mx/site/aviso-privacidad">Aviso de privacidad</a>
    </footer>
   
<?php $this->endBody() ?>

<?=isset($this->params['modales'])?$this->params['modales']:''?> 
</body>
</html>
<?php $this->endPage() ?>

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
    <link rel="apple-touch-icon" sizes="57x57" href="<?= Url::base() ?>/webassets/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= Url::base() ?>/webassets/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= Url::base() ?>/webassets/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= Url::base() ?>/webassets/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= Url::base() ?>/webassets/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= Url::base() ?>/webassets/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= Url::base() ?>/webassets/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= Url::base() ?>/webassets/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Url::base() ?>/webassets/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?= Url::base() ?>/webassets/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Url::base() ?>/webassets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= Url::base() ?>/webassets/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Url::base() ?>/webassets/favicons/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script>
        var baseUrl = "<?=Yii::$app->urlManager->createAbsoluteUrl ( [''] )?>";
    </script>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<section class="donativos-wrapper">
    <div class="container-full">
    <?= $content ?>
    </div>

    <footer>
      <a class="sponsor" href="http://www.2geeksonemonkey.com">Desarrollo por 2 Geeks one Monkey</a>
      <img src="webassets/images/2Geeks-isotipo.png" alt="Desarrollo por 2 Geeks one Monkey">
    </footer>
  </section>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

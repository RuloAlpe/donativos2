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
<body>
<?php $this->beginBody() ?>
<section class="donativos-wrapper">
<!-- <a href="<?=Url::base()?>/site/mis-boletos" class="btn btn-success btn-boletos">Mis donaciones</a> -->
        <?= $content ?>
        <footer class="not-absolute"><a class="sponsor" href="http://www.2geeksonemonkey.com">Desarrollo por 2 Geeks one Monkey</a></footer>
  </section> 

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        
        'webAssets/plugins/ladda/ladda.css',
        'webAssets/css/bootstrap.min.css',
        'webAssets/css/donativos.css.map',
        'webAssets/css/donativos.css'
        
    ];
    public $js = [
        'webAssets/js/bootstrap.min.js',
        'webAssets/plugins/sweet-alert/sweetalert.min.js',
        'webAssets/plugins/ladda/spin.js',
        'webAssets/plugins/ladda/ladda.js',
        'webAssets/js/geeks.js'
    ];
    public $depends = [
         'yii\web\YiiAsset',
         //'yii\bootstrap\BootstrapAsset',
    ];
}

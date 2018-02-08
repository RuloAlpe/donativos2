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
        
        'webassets/css/styles.css',
        'webassets/css/donativos-site.css',
        
    ];
    public $js = [
        'webassets/plugins/sweet-alert/sweetalert.min.js'
    ];
    public $depends = [
         'yii\web\YiiAsset',
         //'yii\bootstrap\BootstrapAsset',
    ];
}

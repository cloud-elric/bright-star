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
class AppAssetClassicTopBarBlank extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/webAssets/';
    public $css = [
        'plugins/ladda/ladda.css',
        'templates/classic/topbar/assets/examples/css/pages/login-v3.min.css',
        'css/site/login.css',
        'css/site-extend.css'
    ];
    public $js = [
        'plugins/ladda/spin.min.js',
        'plugins/ladda/ladda.min.js',
        'js/geeks.js'

    ];
    public $depends = [
        'app\assets\AppAssetClassicTopBar',
    ];
}

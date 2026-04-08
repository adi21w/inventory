<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.css',
        'libs/toastify/toastify.css',
        'libs/perfect-scrollbar/perfect-scrollbar.css',
        'libs/bootstrap-icons/bootstrap-icons.css',
        'css/app.css',
        'css/site.css',
        'libs/fontawesome/all.min.css',
        'libs/sweetalert2/sweetalert2.min.css',
        'css/light-mode.css',
        'css/dark-mode.css'
    ];
    public $js = [
        'libs/perfect-scrollbar/perfect-scrollbar.min.js',
        'libs/toastify/toastify.js',
        // 'libs/mazer/bootstrap.bundle.min.js',
        'libs/mazer/main.js',
        'libs/fontawesome/all.min.js',
        // 'js/helper.js',
        'js/helper-new.js',
        'js/custom.js',
        'libs/sweetalert2/sweetalert2.all.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        // 'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
    ];
}

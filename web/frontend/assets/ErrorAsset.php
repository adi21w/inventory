<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ErrorAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.css',
        'libs/bootstrap-icons/bootstrap-icons.css',
        'css/app.css',
        'css/pages/error.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}

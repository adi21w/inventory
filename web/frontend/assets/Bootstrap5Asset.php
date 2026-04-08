<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class Bootstrap5Asset extends AssetBundle
{
    public $basePath = '@webroot/bootstrap5';
    public $baseUrl = '@web/bootstrap5';

    public $css = [
        'css/bootstrap.min.css',
    ];

    public $js = [
        'js/bootstrap.bundle.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}

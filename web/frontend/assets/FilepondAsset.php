<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class FilepondAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'libs/filepond/filepond.min.css',
        'libs/filepond/filepond-plugin-image-preview.min.css',
    ];
    public $js = [
        'libs/filepond/filepond-plugin-file-validate-size.min.js',
        'libs/filepond/filepond-plugin-file-validate-type.min.js',
        'libs/filepond/filepond-plugin-image-exif-orientation.min.js',
        'libs/filepond/filepond-plugin-image-crop.min.js',
        'libs/filepond/filepond-plugin-image-filter.min.js',
        'libs/filepond/filepond-plugin-image-preview.min.js',
        'libs/filepond/filepond-plugin-image-resize.min.js',
        'libs/filepond/filepond.min.js',
        'libs/filepond/filepond-register.js',
        'libs/filepond/filepond-custom.js',
    ];

    public $depends = [
        \frontend\assets\AppAsset::class, // agar muncul setelah AppAsset (bootstrap.css)
    ];
}

<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'Simple Inventory',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'defaultRoute' => 'site',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'TPS_RM_SESSION',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [],
        ],
        'assetManager' => [
            'bundles' => [
                'kartik\grid\GridViewAsset' => [
                    'bsDependencyEnabled' => false, // Matikan Bootstrap bawaan GridView
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                'kvgrid' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/kartik-v/yii2-grid/messages',
                ],
            ],
        ],

    ],
    'modules' => [
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => '@app/views/layouts/admin',
        ],
        'gridview' => [
            'class' => 'kartik\grid\Module',
            // optional settings
            //'downloadAction' => 'gridview/export/download',
            //'i18n' => []
        ]
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/login',
            'site/logout',
            // 'admin/*',
            'v1/*'
        ],
    ],

    'params' => $params,
];

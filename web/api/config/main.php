<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'name' => 'Simple Inventory API',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'as beforeRequest' => [
        'class' => 'api\components\BeforeRequest',
    ],
    'as jwt' => [
        'class' => 'api\components\JwtMiddleware',
        'except' => [
            'auth/login',
        ],
    ],
    'components' => [
        'request' => [
            'enableCsrfValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['product', 'pack', 'user', 'warehouse', 'rack', 'stock'],
                    'pluralize' => true, // URL: /products, /packs, dsb.
                ],
                'POST auth/login' => 'auth/login',
            ],
        ],
    ],
    'params' => $params,
];

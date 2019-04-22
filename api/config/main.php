<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'response' => [
            'format' => 'json',
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
            'loginUrl' => null,
//            'identityCookie' => ['name' => '_identity-api', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/session'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/post','extraPatterns' => [
                    'POST,OPTIONS likes' => 'likes',
                ]],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/user','extraPatterns' => [
                    'POST,OPTIONS avatar' => 'set-avatar',
                ]],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/system-setting'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/like'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/reply'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/post-class'],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/issue','extraPatterns' => [
                    'GET person' => 'person',
                ]],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/answer'],
            ]
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

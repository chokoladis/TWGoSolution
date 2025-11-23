<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'baseUrl' => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCookieValidation' => false,
        ],

        'response' => [
            'charset' => 'UTF-8',
            'format' => yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                // Устанавливаем Content-Type для JSON
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
                if ($response->data !== null) {
                    $response->data = [
                        'data' => $response->data,
                    ];
                } elseif (!empty($response->errors)) {
                    $response->errors = [
                        'errors' => $response->errors,
                    ];
                }
            },
        ],

        'user' => [
            'identityClass' => 'api\models\User', // Используем модель User из API
            'enableSession' => false,           // REST — stateless (без сессий)
            'enableAutoLogin' => false,
        ],

        'session' => [
            // Отключаем куки-сессии
            'name' => 'api-session',
            'savePath' => sys_get_temp_dir(),
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                ],
            ],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'GET users' => 'user/index',
                'POST users' => 'user/create',
                'POST auth/login' => 'user/login',
                'GET users/<id>' => 'user/view',
//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => 'book',
//                    'pluralize' => false,
//                ],
//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => 'library',
//                    'pluralize' => false,
//                ]
            ],
        ],
    ],

    'params' => $params,
];
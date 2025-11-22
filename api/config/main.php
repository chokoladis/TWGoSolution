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
            // важно для JWT в Authorization: Bearer ...
            'enableCookieValidation' => false,
        ],

        'response' => [
            'charset' => 'UTF-8',
            'format' => yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                // Устанавливаем Content-Type для JSON
                $response->headers->set('Content-Type', 'application/json; charset=UTF-8');
                if ($response->data !== null && $response->isSuccessful) {
                    // единый формат успешного ответа
                    $response->data = [
                        'success' => true,
                        'data' => $response->data,
                    ];
                }
            },
        ],

        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,           // REST — stateless
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
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'user',
                    'pluralize' => false,
                ]
            ],
        ],

        // JWT компонент
//        'jwt' => [
//            'class' => \sizeg\jwt\Jwt::class,
//            'key' => 'твой_секретный_ключ_256_бит_или_больше',
//            'jwtValidationData' => \api\components\JwtValidationData::class,
//        ],
    ],

    'params' => $params,
//
//    'as authenticator' => [
//        'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
//        'except' => ['auth/login', 'auth/refresh', 'options'], // публичные методы
//    ],
];
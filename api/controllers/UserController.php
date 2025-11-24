<?php

namespace api\controllers;

use api\models\errors\CommonError;
use api\models\LoginForm;
use api\models\User;
use api\services\AuthService;
use api\services\TokenService;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * User controller for REST API
 */
class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create'], $actions['login']);

        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
            'languages' => [
                'en',
                'ru',
            ],
        ];

        // Добавляем CORS фильтр
        $behaviors['cors'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'DELETE',],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        $behaviors['authenticator'] = [
            'class' => \sizeg\jwt\JwtHttpBearerAuth::class,
            'except' => ['create', 'login'],
        ];

        return $behaviors;
    }

    public function actionCreate()
    {
        $model = new $this->modelClass();

        $response = \Yii::$app->getResponse();

        try {
            if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')){

                $authService = new AuthService;
                [$isValid, $errors] = $authService->validateRequestRegister($model);

                if (!$isValid) {
                    return $response->setStatusCode(400)->data = [
                        'errors' => $errors
                    ];
                }

                if ($model->save()) {

                    return $response->setStatusCode(201)
                        ->data = ['token' => TokenService::generateToken($model)];

                } elseif (!$model->hasErrors()) {
                    return $response->setStatusCode(400)->data = [
                        'errors' => [
                            new CommonError('Не удалось сохранить данные','error_saved_data')
                            //array_map(function ($error) {}, $model->getErrors())
                        ]
                    ];
                }
            }


            return $response->setStatusCode(400)->data = ['errors' => [
                new CommonError('Не удалось принять данные','error_getting_data')
            ]];

        } catch (\Throwable $exception) {
//            dump($exception->getMessage(), $exception->getFile(), $exception->getLine());
//            die();
            return $response->setStatusCode(400)->data = [
                'errors' => [
                    new CommonError('Произошла непредвиденная ситуация','system_error')
                ]
            ];
        }
    }

    /**
     * @return array JSON ответ с токеном или ошибками
     */
    public function actionLogin()
    {
        $response = \Yii::$app->getResponse();

        $model = new LoginForm();

        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) {
            if ($model->validate() && $model->login()) {
                $user = $model->getUserModel();

                return $response->setStatusCode(200)->data = [
                    'token' => TokenService::generateToken($user),
                ];
            } else {

                return $response->setStatusCode(422)->data = [
                    'errors' => $model->errors,
                ];
            }
        }

        return $response->setStatusCode(400)->data = [
            'errors' => [new CommonError('Не удалось принять данные','error_getting_data')]
        ];
    }
}
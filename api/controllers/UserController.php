<?php

namespace api\controllers;

use api\exceptions\DataHandleException;
use api\models\LoginForm;
use api\models\User;
use api\services\AuthService;
use api\services\JwtHttpBearerService;
use api\services\TokenService;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\ForbiddenHttpException;
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
            'class' => JwtHttpBearerService::class,
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
                    throw new DataHandleException(details: $errors);
                }

                if ($model->save()) {

                    return $response->setStatusCode(201)
                        ->data = ['token' => TokenService::generateToken($model)];

                } elseif (!$model->hasErrors()) {
                    throw new DataHandleException('Не удалось сохранить данные');
//                    $model->getErrors())
                }
            }

            throw new DataHandleException();

        } catch (\Throwable $exception) {
            throw new DataHandleException();
//                    new CommonError('Произошла непредвиденная ситуация','system_error')
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

                throw new DataHandleException(details: $model->errors);
            }
        }

        throw new DataHandleException;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action === 'view') {
            if ($model->id !== \Yii::$app->user->id) {
                throw new ForbiddenHttpException('Нет доступа');
            }
        }
    }
}
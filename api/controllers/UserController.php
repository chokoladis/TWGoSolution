<?php

namespace api\controllers;

use api\models\LoginForm;
use api\models\User;
use api\services\TokenService;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * User controller for REST API
 */
class UserController extends ActiveController
{
    public $modelClass = User::class;

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
            'except' => ['index', 'create', 'login'],
        ];

        return $behaviors;
    }

    public function actionCreate($id)
    {
//        var_dump($id);
        $model = new $this->modelClass();

        try {

//            todo validation
            if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')){

                if ($model->save()) {
                    var_dump('save');
                    TokenService::generateToken($model);
//                    $response = \Yii::$app->getResponse();
//                    $response->setStatusCode(201);
//                    $response->getHeaders()->set('Location', \yii\helpers\Url::toRoute([$this->viewAction, 'id' => $id], true));
                } elseif (!$model->hasErrors()) {
                    throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
                }
            }

        } catch (\Throwable $exception) {
            var_dump($exception->getMessage(), $exception->getFile(), $exception->getLine());
            die();
        }


//        return $model;
        return $this->asJson([
//            'token' => ,
        ]);
    }

    /**
     * Авторизация пользователя и выдача JWT токена
     * 
     * @return array JSON ответ с токеном или ошибками
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(\Yii::$app->getRequest()->getBodyParams(), '')) {
            if ($model->validate() && $model->login()) {
                $user = $model->getUserModel();

                \Yii::$app->response->setStatusCode(200);
                return [
                    'token' => TokenService::generateToken($user),
                    'user' => [
                        'id' => $user->id,
                        'login' => $user->login,
                        'email' => $user->email,
                    ],
                ];
            } else {
                // Ошибки валидации
                \Yii::$app->response->setStatusCode(422);
                return [
                    'errors' => $model->errors,
                ];
            }
        }

        // Если данные не загружены
        \Yii::$app->response->setStatusCode(400);
        return [
            'error' => 'Invalid request. Provide login and password.',
        ];
    }
}
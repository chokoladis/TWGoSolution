<?php

namespace api\controllers;

use api\models\Book;
use api\models\Library;
use api\models\User;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * User controller for REST API
 */
class LibraryController extends ActiveController
{
    public $modelClass = Library::class;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Переопределяем ContentNegotiator чтобы JSON был по умолчанию
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
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        return $behaviors;
    }
}
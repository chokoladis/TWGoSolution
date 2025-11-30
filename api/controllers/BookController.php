<?php

namespace api\controllers;

use api\models\Book;
use api\services\JwtHttpBearerService;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\filters\ContentNegotiator;
use yii\web\Response;

/**
 * User controller for REST API
 */
class BookController extends ActiveController
{
    public $modelClass = Book::class;

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

        $behaviors['cors'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 86400,
            ],
        ];

        $behaviors['authenticator'] = [
            'class' => JwtHttpBearerService::class,
            'except' => ['index', 'view', ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'index' => [
                'pagination' => [
                    'pageSizeParam' => 'limit',
                    'pageParam' => 'page',
                    'defaultPageSize' => 10,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ],
        ]);
    }
}
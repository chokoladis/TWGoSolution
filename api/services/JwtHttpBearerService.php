<?php

namespace api\services;

use sizeg\jwt\JwtHttpBearerAuth;
use yii\web\UnauthorizedHttpException;

class JwtHttpBearerService extends JwtHttpBearerAuth
{
    public function handleFailure($response)
    {
        throw new UnauthorizedHttpException('Вам необходимо авторизоваться');
    }
}

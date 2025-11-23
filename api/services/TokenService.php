<?php

namespace api\services;

use api\models\User;

/**
 * Сервис для работы с JWT токенами
 */
class TokenService
{
    /**
     * Генерирует JWT токен для пользователя
     * 
     * @param User $user Пользователь для которого генерируется токен
     * @return string JWT токен в виде строки
     */
    public static function generateToken(User $user)
    {
        $jwt = \Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();

        $token = $jwt->getBuilder()
            ->issuedBy(\Yii::$app->request->hostInfo)
            ->permittedFor(\Yii::$app->request->hostInfo)
            ->identifiedBy(uniqid('', true), true)
            ->issuedAt($time)
            ->expiresAt($time + 3600 * 24)
            ->withClaim('uid', $user->id)
            ->getToken($signer, $key);

        return (string) $token;
    }
}
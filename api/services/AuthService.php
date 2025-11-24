<?php

namespace api\services;

use api\models\errors\ValidationError;
use api\models\User;

class AuthService
{
    public function validateRequestRegister(User $model)
    {
        [, $errors] = $this->isPasswordValid($model->password);
        $globErrors = !empty($errors) ? $errors : [];

        if (!$model->validate()){
            foreach ($model->getErrors() as $field => $strError){
                $globErrors[] = new ValidationError($field, current($strError), 'validation');
            }
        }

        return [empty($globErrors), $globErrors];
    }

    public function isPasswordValid($password)
    {
        if (strlen($password) < 6) {
            return [false,
                [new ValidationError(
                    'password',
                    'Пароль должен состоять минимум из 6 символов',
                    'password_min_length')
                ]
            ];
        }

        return [true, null];
    }
}
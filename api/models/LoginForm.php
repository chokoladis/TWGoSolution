<?php

namespace api\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $login;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Валидирует данные для входа
     * Для REST API не выполняем реальный login через сессию,
     * только проверяем валидность данных
     *
     * @return bool whether the user credentials are valid
     */
    public function login()
    {
        if ($this->validate()) {
            // Для REST API не используем сессию, только валидацию
            // Реальный login будет через JWT токен
            return $this->getUser() !== null;
        }
        
        return false;
    }

    /**
     * Finds user by [[login]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByLogin($this->login);
        }

        return $this->_user;
    }
    
    /**
     * Публичный метод для получения пользователя (используется в контроллере)
     * 
     * @return User|null
     */
    public function getUserModel()
    {
        return $this->getUser();
    }
}

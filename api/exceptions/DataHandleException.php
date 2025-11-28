<?php

namespace api\exceptions;


use yii\web\HttpException;

class DataHandleException extends HttpException
{
    private ?array $details = [];

    public function __construct(string $message = 'Не удалось обработать данные', $code = 0, $previous = null, ?array $details = [])
    {
        $this->details = $details;

        parent::__construct(400, $message, $code, $previous);
    }
}
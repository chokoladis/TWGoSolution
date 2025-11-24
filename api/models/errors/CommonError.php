<?php

namespace api\models\errors;

class CommonError implements \JsonSerializable
{
    protected $message;
    protected $code;

    public function __construct(string $message, string $code)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function jsonSerialize() : array
    {
        return [
            'message' => $this->message,
            'code' => $this->code,
        ];
    }
}
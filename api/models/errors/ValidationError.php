<?php

namespace api\models\errors;

class ValidationError implements \JsonSerializable
{
    private $field;
    protected $message;
    protected $code;

    public function __construct(string $field, string $message, string $code)
    {
        $this->field = $field;
        $this->message = $message;
        $this->code = $code;
    }

    public function jsonSerialize() : array
    {
        return [
            'field' => $this->field,
            'message' => $this->message,
            'code' => $this->code,
        ];
    }
}
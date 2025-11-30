<?php

namespace api\components;

use yii\web\JsonParser;

class CustomJsonParser extends JsonParser
{
    public function parse($rawBody, $contentType)
    {
        if (empty(trim($rawBody))) {
            return [];
        }
        return parent::parse($rawBody, $contentType);
    }
}
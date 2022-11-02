<?php

namespace App\Validator\Json;

use App\Validator\JsonValidator;

class TransactionValidator extends JsonValidator
{
    protected const SCHEMA = <<<'JSON'
{
    "type": "object",
    "properties": {
        "bin": {
            "type": "string",
            "minLength": 4,
            "maxLength": 8,
            "pattern": "^\\d+$"
        },
        "amount": {
            "type": "string",
            "pattern": "^[\\d\\.]+$"
        },
        "currency": {
            "type": "string",
            "minLength": 3,
            "mxLength": 3
        }
    },
    "required": ["bin","amount","currency"],
    "additionalProperties": false
}
JSON;

    protected function getSchemaDefinition(): string
    {
        return self::SCHEMA;
    }
}
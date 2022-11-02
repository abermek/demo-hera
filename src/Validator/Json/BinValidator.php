<?php

namespace App\Validator\Json;

use App\Validator\JsonValidator;

class BinValidator extends JsonValidator
{
    private const SCHEMA = <<<'JSON'
{
    "type": "object",
    "properties": {
        "country": {
            "type": "object",
            "properties": {
                "alpha2": {
                    "type": "string"
                }
            },
            "required": ["alpha2"],
            "additionalProperties": true
        }
    },
    "required": ["country"],
    "additionalProperties": true
}
JSON;

    protected function getSchemaDefinition(): string
    {
        return self::SCHEMA;
    }
}
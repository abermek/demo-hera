<?php

namespace App\Validator\Json;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class Transaction extends Constraint
{
    public string $invalidJSON = 'This value is not a valid JSON string';
    public string $invalidSchema = '%property%: %message%';
}
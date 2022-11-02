<?php

namespace App\Validator\Json;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class Bin extends Constraint
{
    public string $message = 'Given JSON does not match the expected Bin List response schema';
}
<?php

namespace App\Validator;

use JsonSchema\Validator;
use RuntimeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

abstract class JsonValidator extends ConstraintValidator
{
    abstract protected function getSchemaDefinition(): string;

    public function validate($value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        $json = @json_decode($value);
        if ($json === false) {
            $this->context->buildViolation('This value is not a valid JSON string')->addViolation();
            return;
        }

        $definition = @json_decode($this->getSchemaDefinition());
        if (empty($definition)) {
            throw new RuntimeException(static::class . ' contains invalid or empty Json Schema definition');
        }

        $validator = new Validator();
        $validator->validate($json, $definition);

        if ($validator->isValid()) {
            return;
        }

        foreach ($validator->getErrors() as $error) {
            $this->context
                ->buildViolation(
                    '%property%: %message%',
                    ['%property%' => $error['property'], '%message%' => $error['message']]
                )
                ->addViolation();
        }
    }
}
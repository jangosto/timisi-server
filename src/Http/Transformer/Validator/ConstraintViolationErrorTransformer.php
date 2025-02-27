<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer\Validator;

use Symfony\Component\Validator\ConstraintViolation;

class ConstraintViolationErrorTransformer
{
    public static function constraintViolationErrorToArray(ConstraintViolation $error): array
    {
        return [
            'type' => 'ConstraintViolation',
            'message' => $error->getMessage(),
            'property' => $error->getPropertyPath(),
            'invalidValue' => $error->getInvalidValue(),
        ];
    }
}

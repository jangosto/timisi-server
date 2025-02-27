<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer\Validator;

use Symfony\Component\Validator\ConstraintViolationList;

class ConstraintViolationErrorsTransformer
{
    public static function constraintViolationsToArray(ConstraintViolationList $errors): array
    {
        $result = [];
        $counter = 0;
        while ($errors->has($counter)) {
            $error = $errors->get($counter);
            $result[] = ConstraintViolationErrorTransformer::constraintViolationErrorToArray($error);
            ++$counter;
        }

        return $result;
    }
}

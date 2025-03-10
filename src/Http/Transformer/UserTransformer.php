<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\User\User;

class UserTransformer implements TransformerInterface
{
    public static function userToArray(User $user): array
    {
        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'idNumber' => $user->idNumber,
            'birthDate' => $user->birthDate->format(self::DATE_FORMAT),
        ];

        return $data;
    }
}

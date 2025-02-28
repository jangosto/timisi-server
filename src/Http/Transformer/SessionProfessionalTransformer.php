<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\User as SessionUser;

class SessionProfessionalTransformer implements TransformerInterface
{
    public static function sessionProfessionalToArray(SessionUser $sessionProfessional): array
    {
        return [
            'id' => $sessionProfessional->id,
            'firstName' => $sessionProfessional->firstName,
            'lastName' => $sessionProfessional->lastName,
            'email' => $sessionProfessional->email,
        ];
    }
}

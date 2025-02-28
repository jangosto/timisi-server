<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\User as SessionUser;

class SessionClientTransformer implements TransformerInterface
{
    public static function sessionClientToArray(SessionUser $sessionClient): array
    {
        return [
            'id' => $sessionClient->id,
            'firstName' => $sessionClient->firstName,
            'lastName' => $sessionClient->lastName,
            'email' => $sessionClient->email,
        ];
    }
}

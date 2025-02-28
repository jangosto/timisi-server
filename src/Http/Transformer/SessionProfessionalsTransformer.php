<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\User as SessionUser;
use Domain\Model\Session\Users as SessionUsers;

class SessionProfessionalsTransformer implements TransformerInterface
{
    public static function sessionProfessionalsToArray(SessionUsers $sessionProfessionals): array
    {
        return array_map(
            fn (SessionUser $sessionProfessional) => SessionProfessionalTransformer::sessionProfessionalToArray($sessionProfessional),
            $sessionProfessionals->toArray()
        );
    }
}

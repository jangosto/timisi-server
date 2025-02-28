<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\User as SessionUser;
use Domain\Model\Session\Users as SessionUsers;

class SessionClientsTransformer implements TransformerInterface
{
    public static function sessionClientsToArray(SessionUsers $sessionClients): array
    {
        return array_map(
            fn (SessionUser $sessionClient) => SessionClientTransformer::sessionClientToArray($sessionClient),
            $sessionClients->toArray()
        );
    }
}

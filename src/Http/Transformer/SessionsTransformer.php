<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\Session;
use Domain\Model\Session\Sessions;

class SessionsTransformer implements TransformerInterface
{
    public static function sessionsToArray(Sessions $sessions): array
    {
        return array_map(
            fn (Session $session) => SessionTransformer::sessionToArray($session),
            $sessions->toArray()
        );
    }
}

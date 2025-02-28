<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\Room as SessionRoom;

class SessionRoomTransformer implements TransformerInterface
{
    public static function sessionRoomToArray(SessionRoom $sessionRoom): array
    {
        return [
            'id' => $sessionRoom->id,
            'name' => $sessionRoom->name,
            'capacity' => $sessionRoom->capacity,
        ];
    }
}

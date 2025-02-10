<?php

declare(strict_types=1);

namespace Domain\Model\Room;

use Domain\Model\ObjectNotFoundException;

class RoomNotFoundException extends ObjectNotFoundException
{
    protected $message = 'Room not found';
}

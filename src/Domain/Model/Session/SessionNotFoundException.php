<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\ObjectNotFoundException;

class SessionNotFoundException extends ObjectNotFoundException
{
    protected $message = 'Session not found';
}

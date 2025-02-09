<?php

namespace Domain\Model\Session;

use Domain\Model\ObjectNotFoundException;

class SessionNotFoundException extends ObjectNotFoundException
{
    protected $message = 'Session not found';
}
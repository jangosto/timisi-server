<?php

namespace Domain\Model\User;

use Domain\Model\ObjectNotFoundException;

class UserNotFoundException extends ObjectNotFoundException
{
    protected $message = 'User not found';
}
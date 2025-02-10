<?php

declare(strict_types=1);

namespace Domain\Model\User;

use Domain\Model\ObjectNotFoundException;

class UserNotFoundException extends ObjectNotFoundException
{
    protected $message = 'User not found';
}

<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\ObjectNotFoundException;

class CategoryNotFoundException extends ObjectNotFoundException
{
    protected $message = 'Category not found';
}

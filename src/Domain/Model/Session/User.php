<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\BaseModelWithId;

class User extends BaseModelWithId
{
    public string $firstName;
    public string $lastName;
    public string $email;
}

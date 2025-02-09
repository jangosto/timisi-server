<?php

namespace Domain\Model\Session;

use Domain\Model\BaseModelWithId;

class User extends BaseModelWithId
{
    public string $name;
    public string $email;
}
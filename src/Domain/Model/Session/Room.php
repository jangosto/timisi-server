<?php

namespace Domain\Model\Session;

use Domain\Model\BaseModelWithId;

class Room extends BaseModelWithId
{
    public string $name;
    public int $capacity;
}
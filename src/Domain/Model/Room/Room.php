<?php

declare(strict_types=1);

namespace Domain\Model\Room;

use Domain\Model\BaseModel;

class Room extends BaseModel
{
    public string $name;
    public string $description;
    public int $capacity;
    public float $priceWithVarPerHour;
    public float $vatPercentage;
}

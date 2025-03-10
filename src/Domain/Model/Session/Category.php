<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\BaseModel;

class Category extends BaseModel
{
    public string $name;
    public string $description;
    public float $priceWithVat;
    public float $vatPercentage;
    public int $capacity;
}

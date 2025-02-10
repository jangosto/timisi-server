<?php

declare(strict_types=1);

namespace Domain\Model;

abstract class HydrationCriteria
{
    public static function createEmpty(): static
    {
        return new static();
    }
}

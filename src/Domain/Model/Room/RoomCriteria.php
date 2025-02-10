<?php

declare(strict_types=1);

namespace Domain\Model\Room;

use Domain\Model\Criteria;

class RoomCriteria extends Criteria
{
    private ?int $capacity = null;

    public function filterByCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }
}

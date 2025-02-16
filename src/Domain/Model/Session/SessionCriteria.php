<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\Criteria;
use Domain\Model\DateTimeRange;

class SessionCriteria extends Criteria
{
    private ?DateTimeRange $startDateTime = null;
    private ?DateTimeRange $endDateTime = null;
    private ?string $roomId = null;
    private ?string $professionalId = null;

    public function filterByStartDateTime(DateTimeRange $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function filterByEndDateTime(DateTimeRange $endDateTime): self
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    public function filterByRoomId(string $roomId): self
    {
        $this->roomId = $roomId;

        return $this;
    }

    public function filterByProfessionalId(string $professionalId): self
    {
        $this->professionalId = $professionalId;

        return $this;
    }

    public function getStartDateTime(): ?DateTimeRange
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): ?DateTimeRange
    {
        return $this->endDateTime;
    }

    public function getRoomId(): ?string
    {
        return $this->roomId;
    }

    public function getProfessionalId(): ?string
    {
        return $this->professionalId;
    }
}

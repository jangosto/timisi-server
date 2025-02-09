<?php

namespace Domain\Model\Session;

use Domain\Model\Criteria;
use Domain\Model\DateTimeInterval;

class SessionCriteria extends Criteria
{
    private ?DateTimeInterval $startDateTime = null;
    private ?DateTimeInterval $endDateTime = null;

    public function filterByStartDateTime(DateTimeInterval $startDateTime): self
    {
        $this->$startDateTime = $startDateTime;

        return $this;
    }

    public function filterByEndDateTime(DateTimeInterval $endDateTime): self
    {
        $this->$endDateTime = $endDateTime;

        return $this;
    }

    public function getStartDateTime(): DateTimeInterval
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): DateTimeInterval
    {
        return $this->endDateTime;
    }
}
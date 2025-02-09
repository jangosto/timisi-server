<?php

namespace Domain\Model;

class DateTimeInterval
{
    private ?\DateTimeImmutable $start;
    private ?\DateTimeImmutable $end;

    public function __construct(
        ?\DateTimeImmutable $start = null,
        ?\DateTimeImmutable $end = null,
    ) {
        $this->start = $start;
        $this->end = $end;
    }

    public function getStart(): ?\DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): ?\DateTimeImmutable
    {
        return $this->end;
    }
}
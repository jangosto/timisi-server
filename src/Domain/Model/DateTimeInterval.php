<?php

namespace Domain\Model;

class DateTimeInterval
{
    private ?\DateTimeImmutable $from;
    private ?\DateTimeImmutable $to;

    public function __construct(
        ?\DateTimeImmutable $from = null,
        ?\DateTimeImmutable $to = null,
    ) {
        $this->from = $from;
        $this->to = $to;
    }

    public function getFrom(): ?\DateTimeImmutable
    {
        return $this->from;
    }

    public function getTo(): ?\DateTimeImmutable
    {
        return $this->to;
    }
}
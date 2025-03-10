<?php

declare(strict_types=1);

namespace Domain\Query;

class GetSessionQuery
{
    public function __construct(
        private readonly string $id,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
}

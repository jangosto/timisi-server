<?php

declare(strict_types=1);

namespace Domain\Model;

class BaseModel extends BaseModelWithId
{
    public ?\DateTimeImmutable $createdAt = null;
    public ?\DateTimeImmutable $updatedAt = null;
    public ?\DateTimeImmutable $deletedAt = null;

    public function markAsUpdated(): self
    {
        $now = new \DateTimeImmutable();
        if (\is_null($this->createdAt)) {
            $this->createdAt = clone $now;
        }
        $this->updatedAt = $now;

        return $this;
    }

    public function setAsDeleted(): void
    {
        $this->deletedAt = new \DateTimeImmutable();
    }
}
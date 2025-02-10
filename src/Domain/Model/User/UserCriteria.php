<?php

declare(strict_types=1);

namespace Domain\Model\User;

use Domain\Model\Criteria;

class UserCriteria extends Criteria
{
    private ?string $username = null;

    public function filterByUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }
}

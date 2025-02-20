<?php

declare(strict_types=1);

namespace Domain\Query;

use Domain\Model\User\UserCriteria;

class GetUsersQuery
{
    public function __construct(
        private readonly UserCriteria $criteria,
    ) {
    }

    public function getCriteria(): UserCriteria
    {
        return $this->criteria;
    }
}

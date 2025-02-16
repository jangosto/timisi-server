<?php

declare(strict_types=1);

namespace Domain\Query;

use Domain\Model\Session\SessionCriteria;

class GetSessionsQuery
{
    public function __construct(
        private readonly SessionCriteria $criteria,
    ) {
    }

    public function getCriteria(): SessionCriteria
    {
        return $this->criteria;
    }
}

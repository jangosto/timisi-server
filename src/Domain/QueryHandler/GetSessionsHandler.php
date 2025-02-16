<?php

declare(strict_types=1);

namespace Domain\QueryHandler;

use Domain\Model\Session\SessionRepository;
use Domain\Model\Session\Sessions;
use Domain\Query\GetSessionsQuery;

class GetSessionsHandler
{
    public function __construct(
        private readonly SessionRepository $sessionRepository,
    ) {
    }

    public function handle(GetSessionsQuery $query): Sessions
    {
        return $this->sessionRepository->findBy($query->getCriteria());
    }
}

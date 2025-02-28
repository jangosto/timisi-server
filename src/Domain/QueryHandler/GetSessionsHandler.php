<?php

declare(strict_types=1);

namespace Domain\QueryHandler;

use Domain\Model\Session\SessionHydrationCriteria;
use Domain\Model\Session\SessionRepository;
use Domain\Model\Session\Sessions;
use Domain\Query\GetSessionsQuery;
use Domain\Service\Hydrator\Session\SessionHydratorCollection;

readonly class GetSessionsHandler
{
    public function __construct(
        private SessionRepository $sessionRepository,
        private SessionHydratorCollection $sessionHydrator,
    ) {
    }

    public function handle(GetSessionsQuery $query): Sessions
    {
        $sessions = $this->sessionRepository->findBy($query->getCriteria());

        $this->sessionHydrator->hydrate(
            SessionHydrationCriteria::createEmpty()
                ->addProfessionals()
                ->addClients()
                ->addRoom(),
            $sessions
        );

        return $sessions;
    }
}

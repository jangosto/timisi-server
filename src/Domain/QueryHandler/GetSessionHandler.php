<?php

declare(strict_types=1);

namespace Domain\QueryHandler;

use Domain\Model\Session\Session;
use Domain\Model\Session\SessionCriteria;
use Domain\Model\Session\SessionHydrationCriteria;
use Domain\Model\Session\SessionRepository;
use Domain\Model\Session\Sessions;
use Domain\Query\GetSessionQuery;
use Domain\Service\Hydrator\Session\SessionHydratorCollection;

readonly class GetSessionHandler
{
    public function __construct(
        private SessionRepository $sessionRepository,
        private SessionHydratorCollection $sessionHydrator,
    ) {
    }

    public function handle(GetSessionQuery $query): Session
    {
        $session = $this->sessionRepository->findOneBy(
            SessionCriteria::createById($query->getId())
        );

        $this->sessionHydrator->hydrate(
            SessionHydrationCriteria::createEmpty()
                ->addProfessionals()
                ->addClients()
                ->addRoom(),
            new Sessions([$session])
        );

        return $session;
    }
}

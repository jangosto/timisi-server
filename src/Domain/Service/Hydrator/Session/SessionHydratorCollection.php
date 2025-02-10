<?php

declare(strict_types=1);

namespace Domain\Service\Hydrator\Session;

use Domain\Model\Session\SessionHydrationCriteria;
use Domain\Model\Session\Sessions;

class SessionHydrator
{
    public function __construct(
        private readonly array $hydrators,
    ) {
    }

    public function hydrate(SessionHydrationCriteria $criteria, Sessions $sessions): void
    {
        foreach ($this->hydrators as $hydrator) {
            if ($hydrator->supports($criteria)) {
                $hydrator->hydrate($sessions);
            }
        }
    }
}

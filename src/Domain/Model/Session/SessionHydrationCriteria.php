<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\HydrationCriteria;

class SessionHydrationCriteria extends HydrationCriteria
{
    private bool $hydrateClients = false;
    private bool $hydrateProfessionals = false;
    private bool $hydrateRoom = false;

    public function addClients(): self
    {
        $this->hydrateClients = true;

        return $this;
    }

    public function addProfessionals(): self
    {
        $this->hydrateProfessionals = true;

        return $this;
    }

    public function addRoom(): self
    {
        $this->hydrateRoom = true;

        return $this;
    }

    public function needsClients(): bool
    {
        return $this->hydrateClients;
    }

    public function needsProfessionals(): bool
    {
        return $this->hydrateProfessionals;
    }

    public function needsRoom(): bool
    {
        return $this->hydrateRoom;
    }
}

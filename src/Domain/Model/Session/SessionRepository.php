<?php

namespace Domain\Model\Session;

use Domain\Model\User\SessionCriteria;
use Domain\Model\User\Sessions;

interface SessionRepository
{
    public function create(Session $session): string;

    public function update(Session $session): void;

    public function remove(Session $session): void;

    public function findOneBy(SessionCriteria $criteria): Session;

    public function findBy(SessionCriteria $criteria): Sessions;
}
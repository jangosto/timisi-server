<?php

namespace Domain\Model\Session;

use Domain\Model\BaseRepository;
use Domain\Model\Session\SessionCriteria;
use Domain\Model\Session\Sessions;

interface SessionRepository extends BaseRepository
{
    public function create(Session $session): string;

    public function update(Session $session): void;

    public function remove(Session $session): void;

    public function findOneBy(SessionCriteria $criteria): Session;

    public function findBy(SessionCriteria $criteria): Sessions;
}
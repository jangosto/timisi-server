<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\BaseRepository;

interface SessionRepository extends BaseRepository
{
    public function create(Session $session): string;

    public function update(Session $session): void;

    public function remove(Session $session): void;

    public function findOneBy(SessionCriteria $criteria): Session;

    public function findBy(SessionCriteria $criteria): Sessions;
}

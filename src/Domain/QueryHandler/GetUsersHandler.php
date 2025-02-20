<?php

declare(strict_types=1);

namespace Domain\QueryHandler;

use Domain\Model\User\UserRepository;
use Domain\Model\User\Users;
use Domain\Query\GetUsersQuery;

class GetUsersHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function handle(GetUsersQuery $query): Users
    {
        return $this->userRepository->findBy($query->getCriteria());
    }
}

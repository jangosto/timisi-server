<?php

declare(strict_types=1);

namespace Domain\Model\User;

use Domain\Model\BaseRepository;

interface UserRepository extends BaseRepository
{
    public function create(User $user): string;

    public function update(User $user): void;

    public function remove(User $user): void;

    public function updatePassword(User $user, string $password): void;

    public function findOneBy(UserCriteria $criteria): User;

    public function findBy(UserCriteria $criteria): Users;

    public function getRoles(): array;
}

<?php

namespace Domain\Model\User;

interface UserRepository
{
    public function create(User $user): string;

    public function update(User $user): void;

    public function updatePassword(User $user, string $password): void;

    public function findOneBy(UserCriteria $criteria): User;

    public function findBy(UserCriteria $criteria): Users;

    public function getRoles(): array;

//    public function remove(User $user): void;
}
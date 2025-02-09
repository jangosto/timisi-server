<?php

declare(strict_types=1);

namespace Domain\Model\User;

use Domain\Model\BaseModel;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends BaseModel implements UserInterface, PasswordAuthenticatedUserInterface
{
    public string $username;
    public string $password;
    public array $roles = [];

    public string $firstName;
    public string $lastName;
    public string $idNumber; // DNI
    public \DateTimeImmutable $birthDate;

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
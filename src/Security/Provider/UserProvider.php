<?php

namespace Infrastructure\Security\Provider;

use Domain\Model\User\User;
use Domain\Model\User\UserCriteria;
use Domain\Model\User\UserNotFoundException;
use Domain\Model\User\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findOneBy(
            (new UserCriteria())->filterByUsername($identifier)
        );
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        return $this->loadUserByIdentifier($user->username);
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}
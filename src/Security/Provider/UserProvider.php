<?php

namespace Infrastructure\Security\Provider;

use Domain\Model\User\User;
use Domain\Model\User\UserCriteria;
use Domain\Model\User\UserNotFoundException;
use Domain\Model\User\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

readonly class UserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    /**
     * @param string $identifier
     * @return UserInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->userRepository->findOneBy(
            (new UserCriteria())->filterByUsername($identifier)
        );
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     * @throws UserNotFoundException
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof User) {
            throw new UserNotFoundException();
        }

        return $this->loadUserByIdentifier($user->username);
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass(string $class): bool
    {
        return $class === User::class;
    }
}
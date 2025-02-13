<?php

declare(strict_types=1);

namespace Domain\Service\Hydrator\Session;

use Domain\Model\Collection;
use Domain\Model\HydrationCriteria;
use Domain\Model\Session\SessionHydrationCriteria;
use Domain\Model\Session\User as SessionUser;
use Domain\Model\Session\Users;
use Domain\Model\User\User;
use Domain\Model\User\UserCriteria;
use Domain\Model\User\UserRepository;
use Domain\Service\Hydrator\Hydrator;

class SessionProfessionalsHydrator implements Hydrator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function hydrate(Collection $collection): void
    {
        $professionalIds = [];
        foreach ($collection->toArray() as $session) {
            array_merge($professionalIds, $session->professionalIds);
        }
        $professionalIds = array_unique($professionalIds);

        $professionals = $this->userRepository->findBy(
            UserCriteria::createByIds($professionalIds)
        )->indexedById();

        foreach ($collection->toArray() as $session) {
            $session->professionals = new Users(
                array_map(
                    fn ($professionalId) => $this->userToSessionUser($professionals[$professionalId]),
                    $session->professionalIds
                )
            );
        }
    }

    public function supports(HydrationCriteria $criteria): bool
    {
        return $criteria instanceof SessionHydrationCriteria
            && $criteria->needsProfessionals();
    }

    private function userToSessionUser(User $user): SessionUser
    {
        $sessionUser = new SessionUser();
        $sessionUser->id = $user->id;
        $sessionUser->email = $user->username;
        $sessionUser->firstName = $user->firstName;
        $sessionUser->lastName = $user->lastName;

        return $sessionUser;
    }
}

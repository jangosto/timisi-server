<?php

namespace Infrastructure\DBAL\Model\User;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Domain\Model\City\City;
use Domain\Model\User\User;
use Domain\Model\User\UserCriteria;
use Domain\Model\User\UserNotFoundException;
use Domain\Model\User\UserRepository;
use Domain\Model\User\Users;
use Infrastructure\Service\UserService;
use function Symfony\Component\Translation\t;

class DBALUserRepository implements UserRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $userTableName,
        private readonly string $roleTableName,
        private readonly string $userRoleTableName,
        private readonly UserService $userService,
    ) {}

    public function create(User $user): string
    {
        $user->password = $this->userService->hashPassword($user, $user->password);
        $user->markAsUpdated();

        $this->connection->beginTransaction();

        try {
            $this->connection->insert(
                $this->userTableName,
                $this->userToArray($user)
            );
            $user->id = \strval($this->connection->lastInsertId());

            $this->setUserRoles($user->id, $user->roles);

            $this->connection->commit();

            return $user->id;
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function update(User $user): void
    {
        $user->markAsUpdated();

        $userAsArray = $this->userToArray($user);
        unset($userAsArray['password']);

        $this
            ->connection
            ->update(
                $this->userTableName,
                $userAsArray,
                ['id' => $user->id]
            );

        $this->setUserRoles($user->id, $user->roles);
    }

    public function updatePassword(User $user, string $password): void
    {
        $user->password = $this->userService->hashPassword($user, $password);
        $user->markAsUpdated();

        $this
            ->connection
            ->update(
                $this->userTableName,
                [
                    'password' => $user->password,
                    'updated_at' => $user->updatedAt->format('Y-m-d H:i:s'),
                ],
                ['id' => $user->id]
            );
    }

    public function findOneBy(UserCriteria $criteria): User
    {
        $userAsArray = $this
            ->createQueryBuilderByUserCriteria($criteria)
            ->fetchAssociative();

        if (false === $userAsArray) {
            throw new UserNotFoundException();
        }

        $user = $this->arrayToUser($userAsArray);
        $user->roles = $this->getRolesByUserId($user->id);

        return $user;
    }

    public function findBy(UserCriteria $criteria): Users
    {
        $usersAsArray = $this
            ->createQueryBuilderByUserCriteria($criteria)
            ->fetchAllAssociative();

        return new Users(
            array_map(
                function (array $userAsArray) {
                    $user = $this->arrayToUser($userAsArray);
                    $user->roles = $this->getRolesByUserId($user->id);
                    return $user;
                },
                $usersAsArray
            ),
        );
    }

    public function getRoles(): array
    {
        $roles = $this->connection->fetchAllAssociative(
            'SELECT role FROM ' . $this->roleTableName,
        );

        return array_map(
            fn(array $role) => $role['role'],
            $roles
        );
    }

    private function getRolesByUserId(string $userId): array
    {
        $roles = $this->connection->fetchAllAssociative(
            'SELECT r.role '
            .'FROM ' . $this->userRoleTableName . ' AS ur '
            .'LEFT JOIN ' . $this->roleTableName . ' AS r ON r.id = ur.role_id '
            .'WHERE ur.user_id = :user_id',
            ['user_id' => $userId]
        );

        return array_map(
            fn(array $role) => $role['role'],
            $roles
        );
    }

    private function setUserRoles(
        ?string $userId,
        array $rolesArray,
    ): void {
        $this
            ->connection
            ->delete($this->userRoleTableName, [
                'user_id' => $userId,
            ]);

        $roles = $this->connection->fetchAllAssociative(
            'SELECT id FROM ' . $this->roleTableName . ' WHERE role IN (:roles)',
            ['roles' => $rolesArray],
            ['roles' => ArrayParameterType::STRING]
        );

        foreach ($roles as $role) {
            $this->connection->insert(
                $this->userRoleTableName,
                [
                    'user_id' => $userId,
                    'role_id' => $role['id'],
                ]
            );
        }
    }

    private function userToArray(User $user): array
    {
        return [
            'username' => $user->username,
            'password' => $user->password,
            'created_at' => $user->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $user->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $user->deletedAt ? $user->deletedAt->format('Y-m-d H:i:s') : null,
        ];
    }

    private function arrayToUser(array $data): User
    {
        $user = new User();
        $user->id = $data['id'];
        $user->username = $data['username'];
        $user->password = $data['password'];
        $user->createdAt = new \DateTimeImmutable($data['created_at']);
        $user->updatedAt = new \DateTimeImmutable($data['updated_at']);
        $user->deletedAt = $data['deleted_at'] ? new \DateTimeImmutable($data['deleted_at']) : null;

        return $user;
    }

    private function createQueryBuilderByUserCriteria(UserCriteria $criteria): QueryBuilder
    {
        $queryBuilder = $this
            ->connection
            ->createQueryBuilder()
            ->select('c.*')
            ->from($this->userTableName, 'c')
            ->where('c.deleted_at IS NULL');

        $this->applyCityCriteriaFilters($criteria, $queryBuilder);

        return $queryBuilder;
    }

    private function applyCityCriteriaFilters(UserCriteria $criteria, QueryBuilder $queryBuilder): void
    {
        if (!empty($criteria->getId())) {
            $queryBuilder->andWhere('c.id = :id')
                ->setParameter('id', $criteria->getId());
        }

        if (!empty($criteria->getIds())) {
            $queryBuilder->andWhere('c.id IN (:ids)')
                ->setParameter('ids', $criteria->getIds(), ArrayParameterType::INTEGER);
        }

        if (!empty($criteria->getUsername())) {
            $queryBuilder->andWhere('c.username = :username')
                ->setParameter('username', $criteria->getUsername());
        }
    }
}
<?php

namespace Infrastructure\DBAL\Model\Session;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Domain\Model\Session\Session;
use Domain\Model\Session\SessionCriteria;
use Domain\Model\Session\SessionNotFoundException;
use Domain\Model\Session\SessionRepository;
use Domain\Model\Session\Sessions;

class DBALSessionRepository implements SessionRepository
{
    private const CLIENT_SESSION_CATEGORY_NAME = 'client';
    private const PROFESSIONAL_SESSION_CATEGORY_NAME = 'professional';

    public function __construct(
        private readonly Connection $connection,
        private readonly string $sessionTableName,
        private readonly string $userSessionTableName,
    ) {}

    public function create(Session $session): string
    {
        $this->connection->insert(
            $this->sessionTableName,
            $this->sessionToArray($session)
        );

        $session->id = \strval($this->connection->lastInsertId());

        $this->setSessionClients($session->id, $session->clientIds);
        $this->setSessionProfessionals($session->id, $session->professionalIds);

        return $session->id;
    }

    public function update(Session $session): void
    {
        $session->markAsUpdated();

        $this
            ->connection
            ->update(
                $this->sessionTableName,
                $this->sessionToArray($session),
                ['id' => $session->id]
            );

        $this->setSessionClients($session->id, $session->clientIds);
        $this->setSessionProfessionals($session->id, $session->professionalIds);
    }

    public function remove(Session $session): void
    {
        $session->setAsDeleted();

        $this
            ->connection
            ->update(
                $this->sessionTableName,
                ['deleted_at' => $session->deletedAt->format(self::DATE_TIME_FORMAT)],
                ['id' => $session->id]
            );
    }

    public function findOneBy(SessionCriteria $criteria): Session
    {
        $sessionAsArray = $this
            ->createQueryBuilderBySessionCriteria($criteria)
            ->fetchAssociative();

        if (false === $sessionAsArray) {
            throw new SessionNotFoundException();
        }

        $session = $this->arrayToSession($sessionAsArray);
        $session->clientIds = $this->getClientIdsBySessionId($session->id);
        $session->professionalIds = $this->getProfessionalIdsBySessionId($session->id);

        return $session;
    }

    public function findBy(SessionCriteria $criteria): Sessions
    {
        $sessionsAsArray = $this
            ->createQueryBuilderBySessionCriteria($criteria)
            ->fetchAllAssociative();

        return new Sessions(
            array_map(
                function (array $sessionAsArray) {
                    $session = $this->arrayToSession($sessionAsArray);
                    $session->clientIds = $this->getClientIdsBySessionId($session->id);
                    $session->professionalIds = $this->getProfessionalIdsBySessionId($session->id);
                    
                    return $session;
                },
                $sessionsAsArray
            ),
        );
    }

    private function setSessionClients(string $sessionId, array $clientIds): void
    {
        $this->connection->delete(
            $this->userSessionTableName,
            ['session_id' => $sessionId, 'category' => self::CLIENT_SESSION_CATEGORY_NAME]
        );

        foreach ($clientIds as $clientId) {
            $this->connection->insert(
                $this->userSessionTableName,
                $this->userSessionToArray(
                    $clientId,
                    $sessionId,
                    self::CLIENT_SESSION_CATEGORY_NAME
                )
            );
        }
    }

    private function setSessionProfessionals(string $sessionId, array $professionalIds): void
    {
        $this->connection->delete(
            $this->userSessionTableName,
            ['session_id' => $sessionId, 'category' => self::PROFESSIONAL_SESSION_CATEGORY_NAME]
        );

        foreach ($professionalIds as $professionalId) {
            $this->connection->insert(
                $this->userSessionTableName,
                $this->userSessionToArray(
                    $professionalId,
                    $sessionId,
                    self::PROFESSIONAL_SESSION_CATEGORY_NAME
                )
            );
        }
    }

    private function getClientIdsBySessionId(string $sessionId): array
    {
        $clientIds = $this
            ->connection
            ->createQueryBuilder()
            ->select('us.user_id')
            ->from($this->userSessionTableName, 'us')
            ->where('us.session_id = :session_id')
            ->andWhere('us.category = :category')
            ->setParameter('session_id', $sessionId)
            ->setParameter('category', self::CLIENT_SESSION_CATEGORY_NAME)
            ->fetchAllAssociative();

        return array_map(
            fn(array $clientId) => $clientId['user_id'],
            $clientIds
        );
    }

    private function getProfessionalIdsBySessionId(string $sessionId): array
    {
        $professionalIds = $this
            ->connection
            ->createQueryBuilder()
            ->select('us.user_id')
            ->from($this->userSessionTableName, 'us')
            ->where('us.session_id = :session_id')
            ->andWhere('us.category = :category')
            ->setParameter('session_id', $sessionId)
            ->setParameter('category', self::PROFESSIONAL_SESSION_CATEGORY_NAME)
            ->fetchAllAssociative();

        return array_map(
            fn(array $professionalId) => $professionalId['user_id'],
            $professionalIds
        );
    }

    private function sessionToArray(Session $session): array
    {
        return [
            'id' => $session->id,
            'start_datetime' => $session->startDateTime->format(self::DATE_TIME_FORMAT),
            'end_datetime' => $session->endDateTime->format(self::DATE_TIME_FORMAT),
            'price_with_vat' => $session->priceWithVat,
            'vat_percentage' => $session->vatPercentage,
            'category' => $session->category,
            "capacity" => $session->capacity,
            'created_at' => $session->createdAt->format(self::DATE_TIME_FORMAT),
            'updated_at' => $session->updatedAt->format(self::DATE_TIME_FORMAT),
            'deleted_at' => $session->deletedAt ? $session->deletedAt->format(self::DATE_TIME_FORMAT) : null,
        ];
    }

    private function arrayToSession(array $data): Session
    {
        $session = new Session();
        $session->id = $data['id'];
        $session->priceWithVat = $data['price_with_vat'];
        $session->vatPercentage = $data['vat_percentage'];
        $session->category = $data['category'];
        $session->capacity = $data['capacity'];
        $session->createdAt = new \DateTimeImmutable($data['created_at']);
        $session->updatedAt = new \DateTimeImmutable($data['updated_at']);
        $session->deletedAt = $data['deleted_at'] ? new \DateTimeImmutable($data['deleted_at']) : null;

        return $session;
    }

    private function userSessionToArray(
        string $userId,
        string $sessionId,
        string $category,
    ): array {
        return [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'category' => $category,
        ];
    }

    private function createQueryBuilderBySessionCriteria(SessionCriteria $criteria): QueryBuilder
    {
        $queryBuilder = $this
            ->connection
            ->createQueryBuilder()
            ->select('s.*')
            ->from($this->sessionTableName, 's')
            ->where('s.deleted_at IS NULL');

        $this->applySessionCriteriaFilters($criteria, $queryBuilder);

        return $queryBuilder;
    }

    private function applySessionCriteriaFilters(SessionCriteria $criteria, QueryBuilder $queryBuilder): void
    {
        if (!empty($criteria->getId())) {
            $queryBuilder->andWhere('s.id = :id')
                ->setParameter('id', $criteria->getId());
        }

        if (!empty($criteria->getIds())) {
            $queryBuilder->andWhere('s.id IN (:ids)')
                ->setParameter('ids', $criteria->getIds(), ArrayParameterType::INTEGER);
        }

        if (!empty($criteria->getStartDateTime())) {
            if (!empty($criteria->getStartDateTime()->getFrom())) {
                $queryBuilder->andWhere('s.start_datetime >= :start_datetime_from')
                    ->setParameter('start_datetime_from', $criteria->getStartDateTime()->getFrom()->format(self::DATE_TIME_FORMAT));
            }
            if (!empty($criteria->getStartDateTime()->getTo())) {
                $queryBuilder->andWhere('s.start_datetime <= :start_datetime_to')
                    ->setParameter('start_datetime_to', $criteria->getStartDateTime()->getTo()->format(self::DATE_TIME_FORMAT));
            }
        }

        if (!empty($criteria->getEndDateTime())) {
            if (!empty($criteria->getEndDateTime()->getFrom())) {
                $queryBuilder->andWhere('s.end_datetime >= :end_datetime_from')
                    ->setParameter('end_datetime_from', $criteria->getEndDateTime()->getFrom()->format(self::DATE_TIME_FORMAT));
            }
            if (!empty($criteria->getEndDateTime()->getTo())) {
                $queryBuilder->andWhere('s.end_datetime <= :end_datetime_to')
                    ->setParameter('end_datetime_to', $criteria->getEndDateTime()->getTo()->format(self::DATE_TIME_FORMAT));
            }
        }
    }
}
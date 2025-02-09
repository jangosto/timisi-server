<?php

namespace Infrastructure\DBAL\Model\Session;

use Doctrine\DBAL\Connection;
use Domain\Model\Session\Session;
use Domain\Model\Session\SessionRepository;

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

        if (!empty($session->clientIds)) {
            foreach ($session->clientIds as $clientId) {
                $this->connection->insert(
                    $this->userSessionTableName,
                    $this->userSessionToArray(
                        $clientId,
                        $session->id,
                        self::CLIENT_SESSION_CATEGORY_NAME
                    )
                );
            }
        }

        if (!empty($session->professionalIds)) {
            foreach ($session->professionalIds as $professionalId) {
                $this->connection->insert(
                    $this->userSessionTableName,
                    $this->userSessionToArray(
                        $professionalId,
                        $session->id,
                        self::PROFESSIONAL_SESSION_CATEGORY_NAME
                    )
                );
            }
        }

        return $session->id;
    }

    public function update(Session $session): void
    {

    }

    public function remove(Session $session): void
    {

    }

    public function findOneBy(SessionCriteria $criteria): Session
    {

    }

    public function findBy(SessionCriteria $criteria): Sessions
    {

    }

    private function sessionToArray(Session $session): array
    {
        return [
            'id' => $session->id,
            'start_datetime' => $session->startDateTime->format('Y-m-d H:i:s'),
            'end_datetime' => $session->endDateTime->format('Y-m-d H:i:s'),
            'price_with_vat' => $session->priceWithVat,
            'vat_percentage' => $session->vatPercentage,
            'category' => $session->category,
            "capacity" => $session->capacity,
            'created_at' => $session->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $session->updatedAt->format('Y-m-d H:i:s'),
            'deleted_at' => $session->deletedAt ? $session->deletedAt->format('Y-m-d H:i:s') : null,
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
}
<?php

declare(strict_types=1);

namespace DBAL\Model\Room;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Domain\Model\Room\Room;
use Domain\Model\Room\RoomCriteria;
use Domain\Model\Room\RoomNotFoundException;
use Domain\Model\Room\RoomRepository;
use Domain\Model\Room\Rooms;

class DBALRoomRepository implements RoomRepository
{
    public function __construct(
        private readonly Connection $connection,
        private readonly string $roomTableName,
    ) {
    }

    public function create(Room $room): string
    {
        $room->markAsUpdated();

        $this->connection->beginTransaction();

        try {
            $this->connection->insert(
                $this->roomTableName,
                $this->roomToArray($room)
            );
            $room->id = \strval($this->connection->lastInsertId());

            $this->connection->commit();

            return $room->id;
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function update(Room $room): void
    {
        $room->markAsUpdated();

        $roomAsArray = $this->roomToArray($room);

        $this
            ->connection
            ->update(
                $this->roomTableName,
                $roomAsArray,
                ['id' => $room->id]
            );
    }

    public function remove(Room $room): void
    {
        $room->setAsDeleted();

        $this->connection->update(
            $this->roomTableName,
            ['deleted_at' => $room->deletedAt->format(self::DATE_TIME_FORMAT)],
            ['id' => $room->id]
        );
    }

    public function findOneBy(RoomCriteria $criteria): Room
    {
        $roomAsArray = $this
            ->createQueryBuilderByRoomCriteria($criteria)
            ->fetchAssociative();

        if (false === $roomAsArray) {
            throw new RoomNotFoundException();
        }

        return $this->arrayToRoom($roomAsArray);
    }

    public function findBy(RoomCriteria $criteria): Rooms
    {
        $roomsAsArray = $this
            ->createQueryBuilderByRoomCriteria($criteria)
            ->fetchAllAssociative();

        return new Rooms(
            array_map(
                function (array $roomAsArray) {
                    return $this->arrayToRoom($roomAsArray);
                },
                $roomsAsArray
            )
        );
    }

    private function roomToArray(Room $room): array
    {
        return [
            'name' => $room->name,
            'capacity' => $room->capacity,
            'created_at' => $room->createdAt->format(self::DATE_TIME_FORMAT),
            'updated_at' => $room->updatedAt->format(self::DATE_TIME_FORMAT),
            'deleted_at' => $room->deletedAt ? $room->deletedAt->format(self::DATE_TIME_FORMAT) : null,
        ];
    }

    private function arrayToRoom(array $data): Room
    {
        $room = new Room();
        $room->id = $data['id'];
        $room->name = $data['name'];
        $room->capacity = $data['capacity'];
        $room->createdAt = new \DateTimeImmutable($data['created_at']);
        $room->updatedAt = new \DateTimeImmutable($data['updated_at']);
        $room->deletedAt = $data['deleted_at'] ? new \DateTimeImmutable($data['deleted_at']) : null;

        return $room;
    }

    private function createQueryBuilderByRoomCriteria(RoomCriteria $criteria): QueryBuilder
    {
        $queryBuilder = $this
            ->connection
            ->createQueryBuilder()
            ->select('r.*')
            ->from($this->roomTableName, 'r')
            ->where('r.deleted_at IS NULL');

        $this->applyRoomCriteriaFilters($criteria, $queryBuilder);

        return $queryBuilder;
    }

    private function applyRoomCriteriaFilters(RoomCriteria $criteria, QueryBuilder $queryBuilder): void
    {
        if (!empty($criteria->getId())) {
            $queryBuilder->andWhere('r.id = :id')
                ->setParameter('id', $criteria->getId());
        }

        if (!empty($criteria->getIds())) {
            $queryBuilder->andWhere('r.id IN (:ids)')
                ->setParameter('ids', $criteria->getIds(), ArrayParameterType::INTEGER);
        }

        if (!empty($criteria->getCapacity())) {
            $queryBuilder->andWhere('r.capacity = :capacity')
                ->setParameter('capacity', $criteria->getCapacity());
        }
    }
}

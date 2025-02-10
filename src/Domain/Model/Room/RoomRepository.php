<?php

namespace Domain\Model\Room;

use Domain\Model\BaseRepository;

interface RoomRepository extends BaseRepository
{
    public function create(Room $room): string;

    public function update(Room $room): void;

    public function remove(Room $room): void;

    public function findOneBy(RoomCriteria $criteria): Room;

    public function findBy(RoomCriteria $criteria): Rooms;
}

<?php

declare(strict_types=1);

namespace Domain\Service\Hydrator\Session;

use Domain\Model\Collection;
use Domain\Model\HydrationCriteria;
use Domain\Model\Room\Room;
use Domain\Model\Room\RoomCriteria;
use Domain\Model\Room\RoomRepository;
use Domain\Model\Session\Room as SessionRoom;
use Domain\Model\Session\SessionHydrationCriteria;
use Domain\Service\Hydrator\Hydrator;

class SessionRoomHydrator implements Hydrator
{
    public function __construct(
        private readonly RoomRepository $roomRepository,
    ) {
    }

    public function hydrate(Collection $collection): void
    {
        $roomIds = [];
        foreach ($collection->toArray() as $session) {
            $roomIds[] = $session->roomId;
        }

        $rooms = $this->roomRepository->findBy(
            RoomCriteria::createByIds($roomIds)
        )->indexedById();

        foreach ($collection->toArray() as $session) {
            if (!\is_null($session->roomId)) {
                $session->room = $this->roomTosessionRoom($rooms[$session->roomId]);
            }
        }
    }

    private function roomToSessionRoom(Room $room): SessionRoom
    {
        $sessionRoom = new SessionRoom();
        $sessionRoom->id = $room->id;
        $sessionRoom->name = $room->name;
        $sessionRoom->capacity = $room->capacity;

        return $sessionRoom;
    }

    public function supports(HydrationCriteria $criteria): bool
    {
        return $criteria instanceof SessionHydrationCriteria
            && $criteria->needsRoom();
    }
}

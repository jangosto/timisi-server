<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\Session;

class SessionTransformer implements TransformerInterface
{
    public static function sessionToArray(Session $session): array
    {
        $data = [
            'id' => $session->id,
            'startDateTime' => $session->startDateTime->format(self::DATE_FORMAT),
            'endDateTime' => $session->endDateTime->format(self::DATE_FORMAT),
            'roomId' => $session->roomId,
            'professionalIds' => $session->professionalIds,
            'clientIds' => $session->clientIds,
            'priceWithVat' => $session->priceWithVat,
            'vatPercentage' => $session->vatPercentage,
            'category' => $session->category,
            'capacity' => $session->capacity,
        ];

        if ($session->professionals->count() > 0) {
            $data['professionals'] = SessionProfessionalsTransformer::sessionProfessionalsToArray(
                $session->professionals
            );
        }

        if ($session->clients->count() > 0) {
            $data['clients'] = SessionClientsTransformer::sessionClientsToArray(
                $session->clients
            );
        }

        if (null !== $session->room) {
            $data['room'] = SessionRoomTransformer::sessionRoomToArray(
                $session->room
            );
        }

        return $data;
    }
}

<?php

declare(strict_types=1);

namespace Infrastructure\Http\Transformer;

use Domain\Model\Session\Session;

class SessionTransformer implements TransformerInterface
{
    public static function sessionToArray(Session $session): array
    {
        return [
            'id' => $session->id,
            'startDateTime' => $session->startDateTime->format(self::DATE_FORMAT),
            'endDateTime' => $session->endDateTime->format(self::DATE_FORMAT),
            'roomId' => $session->roomId,
            'professionalId' => $session->professionalIds,
            'clientId' => $session->clientIds,
            'priceWithVat' => $session->priceWithVat,
            'vatPercentage' => $session->vatPercentage,
            'category' => $session->category,
            'capacity' => $session->capacity,
        ];
    }
}

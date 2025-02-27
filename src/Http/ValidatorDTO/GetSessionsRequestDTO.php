<?php

declare(strict_types=1);

namespace Infrastructure\Http\ValidatorDTO;

use Symfony\Component\Validator\Constraints as Assert;

class GetSessionsRequestDTO
{
    #[Assert\DateTime(format: 'Y-m-d H:i:s', message: 'El formato de fecha y hora debe ser YYYY-MM-DD HH:MM:SS')]
    public ?string $startDateTimeFrom;

    #[Assert\DateTime(format: 'Y-m-d H:i:s', message: 'El formato de fecha y hora debe ser YYYY-MM-DD HH:MM:SS')]
    public ?string $startDateTimeTo;

    #[Assert\DateTime(format: 'Y-m-d H:i:s', message: 'El formato de fecha y hora debe ser YYYY-MM-DD HH:MM:SS')]
    public ?string $endDateTimeFrom;

    #[Assert\DateTime(format: 'Y-m-d H:i:s', message: 'El formato de fecha y hora debe ser YYYY-MM-DD HH:MM:SS')]
    public ?string $endDateTimeTo;

    #[Assert\Positive(message: 'El id de la sala debe ser un número positivo')]
    public ?int $roomId;

    #[Assert\Positive(message: 'El id del profesional debe ser un número positivo')]
    public ?int $professionalId;

    #[Assert\Positive(message: 'El id del cliente debe ser un número positivo')]
    public ?int $clientId;

    public function __construct(array $data)
    {
        $this->startDateTimeFrom = $data['startDateTimeFrom'] ?? null;
        $this->startDateTimeTo = $data['startDateTimeTo'] ?? null;
        $this->endDateTimeFrom = $data['endDateTimeFrom'] ?? null;
        $this->endDateTimeTo = $data['endDateTimeTo'] ?? null;
        $this->roomId = $data['roomId'] ?? null;
        $this->professionalId = $data['professionalId'] ?? null;
        $this->clientId = $data['clientId'] ?? null;
    }
}

<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\BaseModel;

class Session extends BaseModel
{
    public \DateTimeImmutable $startDateTime;
    public \DateTimeImmutable $endDateTime;
    public array $clientIds = [];
    public Users $clients;
    public array $professionalIds = [];
    public Users $professionals;
    public float $priceWithVat;
    public float $vatPercentage;
    public string $categoryId;
    public ?string $roomId = null;
    public ?Room $room = null;
    public ?int $capacity = null;

    public function __construct()
    {
        $this->clients = new Users();
        $this->professionals = new Users();
    }
}

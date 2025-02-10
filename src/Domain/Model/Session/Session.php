<?php

declare(strict_types=1);

namespace Domain\Model\Session;

use Domain\Model\BaseModel;

class Session extends BaseModel
{
    private const MULTI_SESSION_CATEGORY_NAME = 'multi_session';
    private const SINGLE_SESSION_CATEGORY_NAME = 'single_session';

    public \DateTimeImmutable $startDateTime;
    public \DateTimeImmutable $endDateTime;
    public array $clientIds = [];
    public Users $clients;
    public array $professionalIds = [];
    public Users $professionals;
    public float $priceWithVat;
    public float $vatPercentage;
    public string $category;
    public ?string $roomId = null;
    public ?Room $room = null;
    public ?int $capacity = null;

    public function __construct()
    {
        $this->clients = new Users();
        $this->professionals = new Users();

        parent::__construct();
    }
}

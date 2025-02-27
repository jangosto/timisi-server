<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class QueryBusController extends AbstractController
{
    public function __construct(
        protected readonly CommandBus $queryBus,
    ) {
    }

    public function ask(object $query)
    {
        return $this->queryBus->handle($query);
    }
}

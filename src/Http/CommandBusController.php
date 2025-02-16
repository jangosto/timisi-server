<?php

declare(strict_types=1);

namespace Infrastructure\Http;

use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class CommandBusController extends AbstractController
{
    public function __construct(
        protected readonly CommandBus $commandBus,
    ) {
    }

    public function execute(object $command)
    {
        return $this->commandBus->handle($command);
    }
}

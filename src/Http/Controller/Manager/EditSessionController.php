<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Manager;

use Domain\Query\GetSessionQuery;
use Infrastructure\Http\Controller\QueryBusController;
use Infrastructure\Service\Menu\MenuService;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditSessionController extends QueryBusController
{
    public function __construct(
        private readonly MenuService $menuService,
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct($commandBus);
    }

    #[Route(
        '/manager/session/{sessionId}',
        name: 'edit_session',
        methods: ['GET']
    )]
    public function index(string $sessionId): Response
    {
        $session = $this->ask(new GetSessionQuery($sessionId));

        return $this->render('manager/session/edit_session.html.twig', [
            'client_name' => 'Bambú Fisioterapia',
            'logo_url' => 'https://www.bambufisioterapia.com/wp-content/uploads/2018/01/bambufisioterapia_logo-e1516723053345.png',
            'primaryColor' => '#a9d65f',
            'borderRadius' => '8px',
            'borderColor' => '#e0e0e0',
            'menu' => $this->menuService->getMenu(
                'get_calendar',
                $this->getUser(),
            ),
            'session' => $session,
        ]);
    }
}

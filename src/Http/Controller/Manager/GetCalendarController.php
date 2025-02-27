<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Manager;

use Domain\Model\DateTimeRange;
use Domain\Model\Session\SessionCriteria;
use Domain\Model\User\UserCriteria;
use Domain\Query\GetSessionsQuery;
use Domain\Query\GetUsersQuery;
use Infrastructure\Http\Controller\QueryBusController;
use Infrastructure\Service\Menu\MenuService;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class GetCalendarController extends QueryBusController
{
    public function __construct(
        private readonly MenuService $menuService,
        private readonly RouterInterface $router,
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct($commandBus);
    }

    #[Route(
        '/manager/calendar/',
        name: 'get_calendar',
        options: [
            'section_name' => 'Calendario',
            'section_icon' => 'calendar-alt',
        ]
    )]
    public function index(Request $request): Response
    {
        $dateStr = $request->query->get('date');

        $calentarDayDate = new \DateTimeImmutable($dateStr ?? 'now');

        $startDateTime = $calentarDayDate->modify('first day of this month')->setTime(0, 0, 0);
        $endDateTime = $calentarDayDate->modify('last day of this month')->setTime(23, 59, 59);

        $professionals = $this->ask(new GetUsersQuery(
            UserCriteria::createEmpty()
                ->filterByRole('ROLE_MANAGER')
        ));

        $sessions = $this->ask(new GetSessionsQuery(
            SessionCriteria::createEmpty()
                ->filterByStartDateTime(
                    new DateTimeRange($startDateTime, $endDateTime)
                )
        ));

        return $this->render('manager/calendar.html.twig', [
            'client_name' => 'Bambú Fisioterapia',
            'logo_url' => 'https://www.bambufisioterapia.com/wp-content/uploads/2018/01/bambufisioterapia_logo-e1516723053345.png',
            'primaryColor' => '#a9d65f',
            'borderRadius' => '8px',
            'borderColor' => '#e0e0e0',
            'menu' => $this->menuService->getMenu(
                'get_calendar',
                $this->getUser(),
            ),
            'date' => $calentarDayDate,
            'professionals' => $professionals,
            'events' => $sessions,
        ]);
    }
}

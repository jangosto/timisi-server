<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Manager;

use Domain\Model\DateTimeRange;
use Domain\Model\Session\SessionCriteria;
use Domain\Query\GetSessionsQuery;
use Domain\Service\Hydrator\Session\SessionHydratorCollection;
use Infrastructure\Http\QueryBusController;
use Infrastructure\Service\Calendar\CalendarService;
use Infrastructure\Service\Menu\MenuService;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class GetCalendarMonthController extends QueryBusController
{
    public function __construct(
        private readonly MenuService $menuService,
        private readonly CalendarService $calendarService,
        private readonly SessionHydratorCollection $sessionHydratorCollection,
        private readonly RouterInterface $router,
        private readonly CommandBus $commandBus,
    ) {
        parent::__construct($commandBus);
    }

    #[Route(
        '/manager/calendar/month/',
        name: 'get_calendar_month',
        options: [
            'section_name' => 'Calendario',
            'section_icon' => 'calendar-alt',
        ]
    )]
    public function index(Request $request): Response
    {
        $month = \intval($request->query->get('month') ?? (new \DateTimeImmutable())->format('m'));
        $year = \intval($request->query->get('year') ?? (new \DateTimeImmutable())->format('Y'));

        $sessions = $this->ask(
            new GetSessionsQuery(
                SessionCriteria::createEmpty()
                    ->filterByStartDateTime(
                        new DateTimeRange(
                            $this->calendarService->getFirstDayOfMonth($month, $year),
                            $this->calendarService->getLastDayOfMonth($month, $year),
                        ),
                    ),
            ),
        );

        $calendarData = [
            'client_name' => 'Bambú Fisioterapia',
            'logo_url' => 'https://www.bambufisioterapia.com/wp-content/uploads/2018/01/bambufisioterapia_logo-e1516723053345.png',
            'menu' => $this->menuService->getMenu(
                $request->attributes->get('_route'),
                $this->getUser(),
            ),
            'month_name' => $this->calendarService->getMonthName($month, 'es_ES'),
            'month' => $month,
            'year' => $year,
            'calendar' => $this->calendarService->getCalendarDataByMonth(
                $month,
                $year,
                $sessions,
            ),
        ];

        return $this->render('manager/calendar_month.html.twig', $calendarData);
    }
}

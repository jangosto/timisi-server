<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Manager;

use Domain\Model\DateTimeRange;
use Domain\Model\Session\SessionCriteria;
use Domain\Query\GetSessionsQuery;
use Infrastructure\Http\QueryBusController;
use Infrastructure\Service\CalendarService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateSessionController extends QueryBusController
{
    #[Route('/manager/session/new/', name: 'new_session_form', methods: ['GET'])]
    public function index(): Response
    {
        $sessions = $this->ask(
            new GetSessionsQuery(
                SessionCriteria::createEmpty()
                    ->filterByStartDateTime(
                        new DateTimeRange(
                            new \DateTimeImmutable('2025-02-01 00:00:00'),
                            new \DateTimeImmutable('2025-02-28 23:59:59'),
                        ),
                    ),
            ),
        );

        $calendarData = [
            'client_name' => 'Bambú Fisioterapia',
            'logo_url' => 'https://www.bambufisioterapia.com/wp-content/uploads/2018/01/bambufisioterapia_logo-e1516723053345.png',
            'sections' => [
                [
                    'name' => 'Inicio',
                    'url' => '/manager/dashboard',
                    'icon' => 'home',
                ],
                [
                    'name' => 'Calendario',
                    'url' => '/manager/calendar',
                    'icon' => 'calendar-alt',
                ],
                [
                    'name' => 'Pacientes',
                    'url' => '/manager/pacientes',
                    'icon' => 'user',
                ],
                [
                    'name' => 'Profesores',
                    'url' => '/manager/profesores',
                    'icon' => 'chalkboard-teacher',
                ],
                [
                    'name' => 'Clases',
                    'url' => '/manager/clases',
                    'icon' => 'dumbbell',
                ],
                [
                    'name' => 'Ajustes',
                    'url' => '/manager/ajustes',
                    'icon' => 'cog',
                ],
            ],
            'section_name' => 'Calendario',
            'month_name' => 'Febrero',
            'year' => 2025,
            'calendar' => CalendarService::getCalendarDataByMonth(
                sessions: $sessions,
            ),
        ];

        return $this->render('manager/calendar_by_month.html.twig', $calendarData);
    }
}

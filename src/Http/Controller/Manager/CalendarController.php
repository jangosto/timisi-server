<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Manager;

use Infrastructure\Service\CalendarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CalendarController extends AbstractController
{
    #[Route('/manager/calendar', name: 'calendar')]
    public function index(): Response
    {
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
            'calendar' => CalendarService::getCalendarDataByMonth(),
        ];

        return $this->render('manager/calendar.html.twig', $calendarData);
    }
}

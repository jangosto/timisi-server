<?php

declare(strict_types=1);

namespace Infrastructure\Http\Controller\Manager;

use Domain\Model\DateTimeRange;
use Domain\Model\Session\SessionCriteria;
use Domain\Model\Session\SessionHydrationCriteria;
use Domain\Model\User\UserCriteria;
use Domain\Query\GetSessionsQuery;
use Domain\Query\GetUsersQuery;
use Domain\Service\Hydrator\Session\SessionHydratorCollection;
use Infrastructure\Http\QueryBusController;
use Infrastructure\Service\Calendar\CalendarService;
use Infrastructure\Service\Menu\MenuService;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\RouterInterface;

class GetCalendarDayController extends QueryBusController
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
        '/manager/calendar/day/',
        name: 'get_calendar_day',
    )]
    public function index(Request $request): Response
    {
        $dateStr = $request->query->get('date');

        try {
            $calentarDayDate = new \DateTimeImmutable($dateStr ?? 'now');

            $startDateTime = $calentarDayDate->setTime(0, 0, 0);
            $endDateTime = $calentarDayDate->setTime(23, 59, 59);

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

            $this->sessionHydratorCollection->hydrate(
                SessionHydrationCriteria::createEmpty()
                    ->addProfessionals()
                    ->addClients()
                    ->addRoom(),
                $sessions
            );

            $sessionsIndexedByUserId = [];
            foreach ($professionals as $professional) {
                $sessionsIndexedByUserId[$professional->id] = [];
            }

            foreach ($sessions as $session) {
                foreach ($session->professionals as $professional) {
                    $sessionsIndexedByUserId[$professional->id][] = $session;
                }
            }

            $this->createProfessionalSlotsArray($sessionsIndexedByUserId, $startDateTime);

            return $this->render('manager/calendar_day.html.twig', [
                'client_name' => 'Bambú Fisioterapia',
                'logo_url' => 'https://www.bambufisioterapia.com/wp-content/uploads/2018/01/bambufisioterapia_logo-e1516723053345.png',
                'menu' => $this->menuService->getMenu(
                    'get_calendar_month',
                    $this->getUser(),
                ),
                'month_name' => $this->calendarService->getMonthName(\intval($calentarDayDate->format('m')), 'es_ES'),
                'date' => $calentarDayDate,
                'sessions' => $sessions,
            ]);
        } catch (\Exception $e) {
            // En caso de error en el formato de fecha, redirigir al día actual
            return $this->redirectToRoute('get_calendar_day');
        }
    }

    private function createProfessionalSlotsArray(array $data, \DateTimeImmutable $startDateTime): array
    {
        $dateWalker = new \DateTime($startDateTime->format('Y-m-d H:i:s'));

        while ($startDateTime->format('d') === $dateWalker->format('d')) {
            $slots = [];
            foreach ($data as $userId => $sessions) {
                $slots[$userId] = [];
                foreach ($sessions as $session) {
                    if (
                        $session->startDateTime >= $dateWalker
                        && $session->endDateTime <= $dateWalker
                    ) {
                        $slots[$userId][] = $session;
                    }
                }
            }

            $dateWalker->modify('+15 minutes');
        }

        return $slots;
    }
}

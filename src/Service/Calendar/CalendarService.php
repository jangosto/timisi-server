<?php

declare(strict_types=1);

namespace Infrastructure\Service\Calendar;

use Domain\Model\Session\Sessions;

class CalendarService
{
    public function getCalendarDataByMonth(
        ?int $month = null,
        ?int $year = null,
        ?Sessions $sessions = null,
    ): array {
        if (null === $month) {
            $month = (int) date('n');
        }

        if (null === $year) {
            $year = (int) date('Y');
        }

        $dateWalker = \DateTime::createFromFormat(
            'Y-n-j',
            \sprintf('%d-%d-%d', $year, $month, 1)
        );

        $weekDayNumber = 1;
        $paintingDays = false;
        while ($dateWalker->format('n') === \strval($month)) {
            $dayData = ['date' => null, 'class' => 'empty'];

            if (
                !$paintingDays
                && \strval($weekDayNumber) === $dateWalker->format('N')
            ) {
                $paintingDays = true;
            }

            if ($paintingDays) {
                $dayData = [
                    'date' => $dateWalker->format('j'),
                    'class' => 'day',
                    'events' => array_filter(
                        $sessions->toArray(),
                        fn ($session) => $session->startDateTime->format('Y-m-d') === $dateWalker->format('Y-m-d')
                    ),
                ];
                $dateWalker->modify('+1 day');
            }

            $dayData['class'] .= $weekDayNumber >= 6
                ? ' weekend'
                : '';

            if ($weekDayNumber >= 7) {
                $weekDayNumber = 1;
            } else {
                ++$weekDayNumber;
            }

            $calendar[] = $dayData;
        }

        while ($weekDayNumber <= 7) {
            $calendar[] = [
                'date' => null,
                'class' => 'empty' . ($weekDayNumber >= 6 ? ' weekend' : ''),
            ];
            ++$weekDayNumber;
        }

        return $calendar;
    }

    public function getMonthName(int $monthNumber, string $locale = 'es_ES'): string
    {
        $monthNames = [
            'es_ES' => [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre',
            ],
        ];

        return $monthNames[$locale][$monthNumber];
    }

    public function getFirstDayOfMonth(int $month, int $year): \DateTimeImmutable
    {
        return new \DateTimeImmutable("$year-$month-01 00:00:00");
    }

    public function getLastDayOfMonth(int $month, int $year): \DateTimeImmutable
    {
        $date = new \DateTimeImmutable("$year-$month-01");
        $lastDay = $date->modify('last day of this month')->setTime(23, 59, 59);

        return $lastDay;
    }
}

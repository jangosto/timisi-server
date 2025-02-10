<?php

declare(strict_types=1);

namespace Infrastructure\Service;

class CalendarService
{
    public static function getCalendarDataByMonth(
        ?int $month = null,
        ?int $year = null,
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
                    'events' => [],
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
}

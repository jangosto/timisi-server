<?php

declare(strict_types=1);

namespace Infrastructure\Http\CriteriaTransformer;

use Domain\Model\DateTimeRange;
use Domain\Model\Session\SessionCriteria;

class SessionCriteriaTransformer
{
    public static function createCriteriaFromArray(array $criteriaAsArray): SessionCriteria
    {
        /** @var SessionCriteria $criteria */
        $criteria = SessionCriteria::createEmpty();

        if (
            isset($criteriaAsArray['startDateTimeFrom'])
            || isset($criteriaAsArray['startDateTimeTo'])
        ) {
            $criteria->filterByStartDateTime(
                new DateTimeRange(
                    isset($criteriaAsArray['startDateTimeFrom'])
                        ? new \DateTimeImmutable($criteriaAsArray['startDateTimeFrom']) : null,
                    isset($criteriaAsArray['startDateTimeTo'])
                        ? new \DateTimeImmutable($criteriaAsArray['startDateTimeTo']) : null,
                )
            );
        }

        if (
            isset($criteriaAsArray['endDateTimeFrom'])
            || isset($criteriaAsArray['endDateTimeTo'])
        ) {
            $criteria->filterByEndDateTime(
                new DateTimeRange(
                    isset($criteriaAsArray['endDateTimeFrom'])
                        ? new \DateTimeImmutable($criteriaAsArray['endDateTimeFrom']) : null,
                    isset($criteriaAsArray['endDateTimeTo'])
                        ? new \DateTimeImmutable($criteriaAsArray['endDateTimeTo']) : null,
                )
            );
        }

        if (isset($criteriaAsArray['roomId'])) {
            $criteria->filterByRoomId($criteriaAsArray['roomId']);
        }

        if (isset($criteriaAsArray['professionalId'])) {
            $criteria->filterByProfessionalId($criteriaAsArray['professionalId']);
        }

        if (isset($criteriaAsArray['clientId'])) {
            $criteria->filterByClientId($criteriaAsArray['clientId']);
        }

        return $criteria;
    }
}

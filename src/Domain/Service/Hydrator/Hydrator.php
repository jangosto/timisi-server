<?php

declare(strict_types=1);

namespace Domain\Service\Hydrator;

use Domain\Model\Collection\Collection;
use Domain\Model\HydrationCriteria;

interface Hydrator
{
    public function supports(HydrationCriteria $criteria): bool;

    public function hydrate(Collection $collection): void;
}

<?php

declare(strict_types=1);

namespace Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Collection extends ArrayCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct($elements);
    }

    public function indexedById(): array
    {
        $indexed = [];
        foreach ($this as $element) {
            $indexed[$element->id()] = $element;
        }

        return $indexed;
    }
}

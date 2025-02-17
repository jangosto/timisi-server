<?php

declare(strict_types=1);

namespace Infrastructure\Service\Menu;

class Menu
{
    public function __construct(
        public readonly MenuSections $sections,
    ) {
    }
}

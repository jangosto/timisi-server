<?php

declare(strict_types=1);

namespace Infrastructure\Service\Menu;

class MenuSection
{
    public function __construct(
        public string $name,
        public string $icon,
        public string $route,
        public bool $active = false,
    ) {
    }
}

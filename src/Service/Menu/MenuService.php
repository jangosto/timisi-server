<?php

declare(strict_types=1);

namespace Infrastructure\Service\Menu;

use Domain\Model\User\User;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

class MenuService
{
    public function __construct(
        private readonly RouterInterface $router,
    ) {
    }

    public function getMenu(string $activeSectionName, ?User $user): Menu
    {
        $routes = $this->getFilteredRoutes('/^\/manager/');

        $menu = new MenuSections();

        /** @var Route $route */
        foreach ($routes as $routeName => $route) {
            if (!\is_null($route->getOption('section_name'))) {
                $roles = $route->getRequirement('_role') ?? [];

                // if (!empty(array_intersect($user->getRoles(), $roles))) {
                $menu->add(
                    new MenuSection(
                        $route->getOption('section_name'),
                        $route->getOption('section_icon'),
                        $routeName,
                        $routeName === $activeSectionName ? true : false
                    )
                );
                // }
            }
        }

        return new Menu($menu);
    }

    private function getFilteredRoutes(string $pattern)
    {
        $routes = $this->router->getRouteCollection();
        $filteredRoutes = [];
        foreach ($routes as $name => $route) {
            if (preg_match($pattern, $route->getPath())) {
                $filteredRoutes[$name] = $route;
            }
        }

        return $filteredRoutes;
    }
}

<?php

namespace Phpillip\Routing;

use Symfony\Component\Routing\RouteCollectionBuilder as BaseRouteCollectionBuilder;

/**
 * Route Collection Builder
 */
class RouteCollectionBuilder extends BaseRouteCollectionBuilder
{
    /**
     * {@inheritdoc}
     */
    public function add($path, $controller, $name = null)
    {
        $route = new Route($path);
        $route->setDefault('_controller', $controller);
        $this->addRoute($route, $name);

        return $route;
    }
}

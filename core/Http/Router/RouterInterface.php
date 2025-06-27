<?php
declare(strict_types=1);

namespace Core\Http\Router;

use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Route\RouteInterface;

interface RouterInterface
{
    /**
     * Add a route to the router.
     *
     * @param RouteInterface $route
     *
     * @return void
     */
    public function addRoute(RouteInterface $route): void;

    /**
     * Get middleware queue.
     *
     * @return MiddlewareQueueInterface
     */
    public function getMiddlewareQueue(): MiddlewareQueueInterface;

    /**
     * Dispatch the request to the appropriate route.
     *
     * @return never
     */
    public function dispatch(): never;

    /**
     * Match the request to a route.
     *
     * @param string $method
     * @param string $path
     *
     * @return RouteInterface|null
     */
    public function match(string $method, string $path): ?RouteInterface;
}

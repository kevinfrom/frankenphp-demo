<?php
declare(strict_types=1);

namespace Core\Http\Router;

use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Request\RequestHandlerInterface;

interface RouterInterface
{
    /**
     * Add a route to the router.
     *
     * @param string[] $methods The HTTP methods for the route, e.g., 'GET', 'POST', etc.
     * @param string $path
     * @param callable $handler
     *
     * @return void
     */
    public function addRoute(array $methods, string $path, callable $handler): void;

    /**
     * Add a GET and HEAD route to the router.
     *
     * @param string $path
     * @param callable $handler
     * @return void
     */
    public function get(string $path, callable $handler): void;

    /**
     * Add a POST route to the router.
     *
     * @param string $path
     * @param callable $handler
     * @return void
     */
    public function post(string $path, callable $handler): void;

    /**
     * Add a PUT route to the router.
     *
     * @param string $path
     * @param callable $handler
     * @return void
     */
    public function put(string $path, callable $handler): void;

    /**
     * Add a PATCH route to the router.
     *
     * @param string $path
     * @param callable $handler
     * @return void
     */
    public function patch(string $path, callable $handler): void;

    /**
     * Add a DELETE route to the router.
     *
     * @param string $path
     * @param callable $handler
     * @return void
     */
    public function delete(string $path, callable $handler): void;

    /**
     * Register a redirect route.
     *
     * @param string $path
     * @param string $to
     * @param int $statusCode
     * @param string[] $methods
     *
     * @return void
     */
    public function redirect(string $path, string $to, int $statusCode = 302, array $methods = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE']): void;

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
     * @return RequestHandlerInterface|null
     */
    public function match(string $method, string $path): ?RequestHandlerInterface;
}

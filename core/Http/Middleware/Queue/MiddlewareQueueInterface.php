<?php

declare(strict_types=1);

namespace Core\Http\Middleware\Queue;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request\RequestHandlerInterface;

interface MiddlewareQueueInterface
{
    /**
     * Register a middleware.
     *
     * @param class-string|object $middleware
     *
     * @return void
     */
    public function addMiddleware(string|object $middleware): void;

    /**
     * Rewind the middleware queue to the beginning.
     *
     * @return void
     */
    public function rewind(): void;

    /**
     * Get the current position in the middleware queue.
     *
     * @return MiddlewareInterface|RequestHandlerInterface
     */
    public function current(): MiddlewareInterface|RequestHandlerInterface;

    /**
     * Move position to the next middleware in the queue.
     *
     * @return void
     */
    public function next(): void;

    /**
     * Check if the current position is valid in the middleware queue.
     *
     * @return bool
     */
    public function valid(): bool;
}

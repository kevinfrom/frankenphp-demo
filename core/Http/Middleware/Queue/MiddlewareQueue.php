<?php

declare(strict_types=1);

namespace Core\Http\Middleware\Queue;

use ArrayObject;
use Core\Container\Container;
use Core\Container\ContainerException;
use Core\Container\NotFoundException;
use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request\RequestHandlerInterface;

final class MiddlewareQueue implements MiddlewareQueueInterface
{
    protected readonly ArrayObject $queue;
    protected int $position = 0;

    public function __construct(public Container $container)
    {
        $this->queue = new ArrayObject();
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     * @throws ContainerException
     */
    public function addMiddleware(object|string $middleware): void
    {
        if (is_string($middleware)) {
            if ($this->container->has($middleware)) {
                $middleware = $this->container->get($middleware);
            } else {
                $middleware = new $middleware();
            }
        }

        $this->queue->append($middleware);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @inheritDoc
     */
    public function current(): MiddlewareInterface|RequestHandlerInterface
    {
        return $this->queue->offsetGet($this->position);
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->queue->offsetExists($this->position);
    }
}

<?php
declare(strict_types=1);

namespace Core\Http\Runner;

use Core\Http\Exceptions\ServerErrors\InternalErrorException;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;

final class HttpRunner implements HttpRunnerInterface
{
    protected MiddlewareQueueInterface $middlewareQueue;

    /**
     * @inheritDoc
     */
    public function run(MiddlewareQueueInterface $middlewareQueue, ServerRequestInterface $request): ServerResponseInterface
    {
        $middlewareQueue->rewind();
        $this->middlewareQueue = $middlewareQueue;

        return $this->handle($request);
    }

    /**
     * @inheritDoc
     * @throws InternalErrorException If no response is returned from the middleware queue.
     */
    public function handle(ServerRequestInterface $request): ServerResponseInterface
    {
        if ($this->middlewareQueue->valid()) {
            $middleware = $this->middlewareQueue->current();
            $this->middlewareQueue->next();

            return $middleware->handle($request, $this);
        }

        throw new InternalErrorException('Middleware queue exhausted without returning a response.');
    }
}

<?php

declare(strict_types=1);

namespace Core\Http\Runner;

use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;

interface HttpRunnerInterface extends RequestHandlerInterface
{
    /**
     * Run the HTTP runner.
     *
     * @param MiddlewareQueueInterface $middlewareQueue The middleware queue to process.
     * @param ServerRequestInterface   $request The server request to process.
     *
     * @return ServerResponseInterface
     */
    public function run(
        MiddlewareQueueInterface $middlewareQueue,
        ServerRequestInterface $request
    ): ServerResponseInterface;
}

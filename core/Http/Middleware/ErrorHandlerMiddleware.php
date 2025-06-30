<?php

declare(strict_types=1);

namespace Core\Http\Middleware;

use Core\Error\ErrorRendererInterface;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;
use Throwable;

final readonly class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function __construct(protected ErrorRendererInterface $errorRenderer)
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $next): ServerResponseInterface
    {
        try {
            return $next->handle($request);
        } catch (Throwable $e) {
            return $this->errorRenderer->renderException($e);
        }
    }
}

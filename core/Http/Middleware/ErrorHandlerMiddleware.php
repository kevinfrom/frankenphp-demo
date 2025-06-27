<?php
declare(strict_types=1);

namespace Core\Http\Middleware;

use Core\Http\Exceptions\HttpException;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponse;
use Core\Http\Response\ServerResponseInterface;

final readonly class ErrorHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $next): ServerResponseInterface
    {
        try {
            return $next->handle($request);
        } catch (HttpException $e) {
            return new ServerResponse($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}

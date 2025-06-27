<?php
declare(strict_types=1);

namespace Core\Http\Middleware;

use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;

interface MiddlewareInterface
{
    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $next
     *
     * @return ServerResponseInterface
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $next): ServerResponseInterface;
}

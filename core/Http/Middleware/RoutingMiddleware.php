<?php
declare(strict_types=1);

namespace Core\Http\Middleware;

use Core\Http\Exceptions\ClientErrors\NotFoundException;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;
use Core\Http\Router\RouterInterface;

final readonly class RoutingMiddleware implements MiddlewareInterface
{
    public function __construct(public RouterInterface $router)
    {
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $next): ServerResponseInterface
    {
        $route = $this->router->match($request->getMethod(), $request->getPath());

        if (!$route) {
            throw new NotFoundException();
        }

        $this->router->getMiddlewareQueue()->addMiddleware($route);

        return $next->handle($request);
    }
}

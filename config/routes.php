<?php
declare(strict_types=1);

use App\Middleware\TimerMiddleware;
use Core\Container\StaticContainer;
use Core\Http\Middleware\ErrorHandlerMiddleware;
use Core\Http\Middleware\RoutingMiddleware;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;
use Core\Http\Route\RouteInterface;
use Core\Http\Route\StaticRoute;
use Core\Http\Router\RouterInterface;

/** @var StaticContainer $container */
$router = $container->get(RouterInterface::class);

$router->getMiddlewareQueue()->addMiddleware(TimerMiddleware::class);
$router->getMiddlewareQueue()->addMiddleware(ErrorHandlerMiddleware::class);
$router->getMiddlewareQueue()->addMiddleware(RoutingMiddleware::class);

$router->addRoute(new StaticRoute([RouteInterface::GET, RouteInterface::HEAD], '/', function (ServerRequestInterface $request): ServerResponseInterface {
    return response('Hello World!');
}));

$router->addRoute(new StaticRoute([RouteInterface::GET, RouteInterface::HEAD], '/about', function (): ServerResponseInterface {
    return response('This project runs using FrankenPHP and is built entirely from scratch!');
}));

$router->addRoute(new StaticRoute([RouteInterface::GET, RouteInterface::HEAD], '/redirect', function (): ServerResponseInterface {
    return redirect('/about');
}));

return $router;

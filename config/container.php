<?php
declare(strict_types=1);

use App\Middleware\TimerMiddleware;
use Core\Clock\Clock;
use Core\Config\Config;
use Core\Config\ConfigInterface;
use Core\Container\StaticContainer;
use Core\Http\Middleware\Queue\MiddlewareQueue;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Middleware\RoutingMiddleware;
use Core\Http\Request\Factory\RequestFactory;
use Core\Http\Request\Factory\RequestFactoryInterface;
use Core\Http\Response\Rendering\ServerResponseRenderer;
use Core\Http\Response\Rendering\ServerResponseRendererInterface;
use Core\Http\RouteParser\RouteParser;
use Core\Http\RouteParser\RouteParserInterface;
use Core\Http\Router\Router;
use Core\Http\Router\RouterInterface;
use Core\Http\Runner\HttpRunner;
use Core\Http\Runner\HttpRunnerInterface;
use Psr\Clock\ClockInterface;

$container = new StaticContainer();

/**
 * Singletons
 */
$container->bind(MiddlewareQueueInterface::class, function (StaticContainer $container): MiddlewareQueueInterface {
    return new MiddlewareQueue($container);
}, true);

$container->bind(RouterInterface::class, function (StaticContainer $container): RouterInterface {
    $parser               = $container->get(RouteParserInterface::class);
    $runner               = $container->get(HttpRunnerInterface::class);
    $serverRequestFactory = $container->get(RequestFactoryInterface::class);
    $middlewareQueue      = $container->get(MiddlewareQueueInterface::class);
    $renderer             = $container->get(ServerResponseRendererInterface::class);

    return new Router($parser, $runner, $serverRequestFactory, $middlewareQueue, $renderer);
}, true);

/**
 * Factories
 */
$container->bind(ConfigInterface::class, fn() => Config::getInstance());

$container->bind(RoutingMiddleware::class, function (StaticContainer $container): RoutingMiddleware {
    $router = $container->get(RouterInterface::class);

    return new RoutingMiddleware($router);
});

$container->bind(TimerMiddleware::class, function (StaticContainer $container): TimerMiddleware {
    $clock = $container->get(ClockInterface::class);

    return new TimerMiddleware($clock);
});

$container->bind(RequestFactoryInterface::class, function (StaticContainer $container): RequestFactoryInterface {
    $routeParser = $container->get(RouteParserInterface::class);

    return new RequestFactory($routeParser);
});

/**
 * Services
 */
$container->bind(HttpRunnerInterface::class, HttpRunner::class);
$container->bind(RouteParserInterface::class, RouteParser::class);
$container->bind(ClockInterface::class, Clock::class);
$container->bind(ServerResponseRendererInterface::class, ServerResponseRenderer::class);

return $container;

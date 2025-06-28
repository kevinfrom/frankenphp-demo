<?php
declare(strict_types=1);

namespace Core\Container\ServiceProviders;

use Core\Clock\Clock;
use Core\Config\Config;
use Core\Config\ConfigInterface;
use Core\Container\Container;
use Core\Container\ContainerException;
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

final readonly class CoreServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     * @throws ContainerException
     */
    public function register(Container $container): void
    {
        /** Singletons */
        $container->bind(MiddlewareQueueInterface::class, function (Container $container): MiddlewareQueue {
            return new MiddlewareQueue($container);
        }, true);

        $container->bind(RouterInterface::class, function (Container $container): RouterInterface {
            $parser = $container->get(RouteParserInterface::class);
            $runner = $container->get(HttpRunnerInterface::class);
            $serverRequestFactory = $container->get(RequestFactoryInterface::class);
            $middlewareQueue = $container->get(MiddlewareQueueInterface::class);
            $renderer = $container->get(ServerResponseRendererInterface::class);

            return new Router($parser, $runner, $serverRequestFactory, $middlewareQueue, $renderer);
        }, true);

        /** Factories */
        $container->bind(ConfigInterface::class, function (): ConfigInterface {
            return Config::getInstance();
        });

        $container->bind(RoutingMiddleware::class, function (Container $container): RoutingMiddleware {
            $router = $container->get(RouterInterface::class);

            return new RoutingMiddleware($router);
        });

        $container->bind(RequestFactoryInterface::class, function (Container $container): RequestFactoryInterface {
            $routeParser = $container->get(RouteParserInterface::class);

            return new RequestFactory($routeParser);
        });

        /** Services */
        $container->bind(HttpRunnerInterface::class, HttpRunner::class);
        $container->bind(RouteParserInterface::class, RouteParser::class);
        $container->bind(ClockInterface::class, Clock::class);
        $container->bind(ServerResponseRendererInterface::class, ServerResponseRenderer::class);
    }
}

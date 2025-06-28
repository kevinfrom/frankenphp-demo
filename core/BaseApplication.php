<?php
declare(strict_types=1);

namespace Core;

use Core\Clock\Clock;
use Core\Config\Config;
use Core\Config\ConfigInterface;
use Core\Container\Container;
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
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class BaseApplication
{
    public readonly Container $container;

    public function __construct()
    {
        $this->container = $this->services(new Container());

        $router = $this->container->get(RouterInterface::class);
        $this->middleware($router->getMiddlewareQueue());
        $this->routes($router);

        $this->bootstrap();
    }

    /**
     * Register services in the container.
     *
     * @param Container $container
     * @return Container
     * @throws ContainerExceptionInterface
     */
    function services(Container $container): Container
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

        return $container;
    }

    /**
     * Register global middleware in the router.
     *
     * @param MiddlewareQueueInterface $middleware
     * @return void
     */
    public function middleware(MiddlewareQueueInterface $middleware): void
    {
    }

    /**
     * Register routes in the router.
     *
     * @param RouterInterface $router
     * @return void
     */
    public function routes(RouterInterface $router): void
    {
    }

    /**
     * Bootstrap the application and load configuration.
     *
     * @return void
     */
    abstract function bootstrap(): void;

    /**
     * Run the application.
     *
     * @return never
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    final public function run(): never
    {
        $router = $this->container->get(RouterInterface::class);
        $router->dispatch();
        exit;
    }
}

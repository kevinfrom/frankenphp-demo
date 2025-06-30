<?php

declare(strict_types=1);

namespace Core;

use Core\Container\Container;
use Core\Container\ServiceProviders\CoreServiceProvider;
use Core\Http\Middleware\ErrorHandlerMiddleware;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Middleware\RoutingMiddleware;
use Core\Http\Router\RouterInterface;
use Psr\Container\ContainerExceptionInterface;
use ReflectionException;

abstract class BaseApplication
{
    public readonly Container $container;

    public function __construct()
    {
        $this->bootstrap();

        $this->container = $this->services(container());

        $router = $this->container->get(RouterInterface::class);
        $this->middleware($router->getMiddlewareQueue());
        $this->routes($router);
    }

    /**
     * Register services in the container.
     *
     * @param Container $container
     *
     * @return Container
     */
    public function services(Container $container): Container
    {
        new CoreServiceProvider()->register($container);

        return $container;
    }

    /**
     * Register global middleware in the router.
     *
     * @param MiddlewareQueueInterface $middleware
     *
     * @return void
     */
    public function middleware(MiddlewareQueueInterface $middleware): void
    {
        $middleware->addMiddleware(ErrorHandlerMiddleware::class);
        $middleware->addMiddleware(RoutingMiddleware::class);
    }

    /**
     * Register routes in the router.
     *
     * @param RouterInterface $router
     *
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
    abstract public function bootstrap(): void;

    /**
     * Run the application.
     *
     * @return never
     * @throws ContainerExceptionInterface
     * @throws ReflectionException
     */
    final public function run(): never
    {
        $router = $this->container->get(RouterInterface::class);
        $router->dispatch();
        exit;
    }
}

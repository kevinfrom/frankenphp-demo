<?php

declare(strict_types=1);

namespace Core;

use Core\Config\ConfigInterface;
use Core\Container\Container;
use Core\Container\ContainerException;
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

    /**
     * @throws ContainerException
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function __construct()
    {
        $this->bootstrap();
    }

    /**
     * Configure the application.
     *
     * @param ConfigInterface $config
     *
     * @return ConfigInterface
     */
    public function configuration(ConfigInterface $config): ConfigInterface
    {
        $config->set([
            'debug' => true,
            'App'   => [
                'timezone' => (env('APP_TIMEZONE') ?: env('TZ')) ?: 'UTC',
            ],
            'Error' => [
                'templates' => [
                    '400' => 'Errors' . DS . '400',
                    '500' => 'Errors' . DS . '500',
                    'dev' => 'Errors' . DS . 'dev',
                ],
            ],
        ]);

        return $config;
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
     * @throws ContainerException
     * @throws ReflectionException
     */
    public function bootstrap(): void
    {
        $this->container = $this->services(container());

        $config = $this->container->get(ConfigInterface::class);
        $this->configuration($config);

        ini_set('date.timezone', $config->get('App.timezone'));

        $router = $this->container->get(RouterInterface::class);
        $this->middleware($router->getMiddlewareQueue());
        $this->routes($router);
    }

    /**
     * Run the application.
     *
     * @return string
     * @throws ContainerException
     * @throws ReflectionException
     */
    final public function run(): string
    {
        $router = $this->container->get(RouterInterface::class);

        return $router->dispatch();
    }
}

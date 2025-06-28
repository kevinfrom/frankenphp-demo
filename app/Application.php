<?php
declare(strict_types=1);

namespace App;

use App\Container\ServiceProviders\AppServiceProvider;
use App\Middleware\TimerMiddleware;
use Core\BaseApplication;
use Core\Config\Config;
use Core\Container\Container;
use Core\Http\Middleware\ErrorHandlerMiddleware;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Middleware\RoutingMiddleware;
use Core\Http\Router\RouterInterface;
use Spatie\Ignition\Ignition;

final class Application extends BaseApplication
{
    /**
     * @inheritDoc
     */
    public function services(Container $container): Container
    {
        $container = parent::services($container);

        new AppServiceProvider()->register($container);

        return $container;
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueueInterface $middleware): void
    {
        $middleware->addMiddleware(TimerMiddleware::class);
        $middleware->addMiddleware(ErrorHandlerMiddleware::class);
        $middleware->addMiddleware(RoutingMiddleware::class);
    }

    /**
     * @inheritDoc
     */
    public function routes(RouterInterface $router): void
    {
        $router->get('/', fn() => response('Hello World!'));

        $router->get('/about', fn() => response('This project runs using FrankenPHP and is built entirely from scratch!'));

        $router->redirect('/redirect', '/about');
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        require CONFIG_DIR . '/helpers.php';
        require CONFIG_DIR . '/configuration.php';

        if (Config::getInstance()->get('debug')) {
            Ignition::make()->register();
        }
    }
}

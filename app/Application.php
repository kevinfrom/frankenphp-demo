<?php
declare(strict_types=1);

namespace App;

use App\Container\ServiceProviders\AppServiceProvider;
use App\Middleware\AppNameMiddleware;
use App\Middleware\TimerMiddleware;
use Core\BaseApplication;
use Core\Container\Container;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Router\RouterInterface;

final class Application extends BaseApplication
{
    /**
     * @inheritDoc
     */
    public function services(Container $container): Container
    {
        new AppServiceProvider()->register($container);

        return parent::services($container);
    }

    /**
     * @inheritDoc
     */
    public function middleware(MiddlewareQueueInterface $middleware): void
    {
        parent::middleware($middleware);

        $middleware->addMiddleware(TimerMiddleware::class);
        $middleware->addMiddleware(AppNameMiddleware::class);
    }

    /**
     * @inheritDoc
     */
    public function routes(RouterInterface $router): void
    {
        $router->get('/error', function () {
            throw new \Core\Http\Exceptions\ServerErrors\InternalErrorException('Test exception');
        });

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
    }
}

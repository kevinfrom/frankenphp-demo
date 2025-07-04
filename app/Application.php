<?php

declare(strict_types=1);

namespace App;

use App\Controller\ErrorController;
use App\Middleware\AppNameMiddleware;
use App\Middleware\TimerMiddleware;
use Core\BaseApplication;
use Core\Config\ConfigInterface;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Router\RouterInterface;
use function Core\redirect;
use function Core\view;

final class Application extends BaseApplication
{
    #[\Override]
    public function configuration(ConfigInterface $config): ConfigInterface
    {
        $config = parent::configuration($config);
        $config->set('App.name', 'FrankenPHP demo');

        return $config;
    }

    /**
     * @inheritDoc
     */
    #[\Override]
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
        $router->get('/', fn() => view('Pages/index'));
        $router->get('/about', fn() => view('Pages/about'));

        $router->get('/redirect', fn() => view('Pages/redirect'));
        $router->post('/redirect', fn() => redirect('/about'));

        $router->controller(['GET', 'HEAD'], '/error/404', ErrorController::class, 'throw404');
        $router->controller(['GET', 'HEAD'], '/error/500', ErrorController::class, 'throw500');
    }
}

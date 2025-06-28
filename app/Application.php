<?php
declare(strict_types=1);

namespace App;

use App\Container\ServiceProviders\AppServiceProvider;
use App\Middleware\AppNameMiddleware;
use App\Middleware\TimerMiddleware;
use Core\BaseApplication;
use Core\Container\Container;
use Core\Http\Exceptions\ClientErrors\NotFoundException;
use Core\Http\Exceptions\ServerErrors\InternalErrorException;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Router\RouterInterface;
use function Core\view;

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
        $router->get('/error404', fn() => throw new NotFoundException('Test 404 exception'));
        $router->get('/error500', fn() => throw new InternalErrorException('Test 500 exception'));

        $router->get('/', fn() => view('Pages/index'));
        $router->get('/about', fn() => view('Pages/about'));

        $router->redirect('/redirect', '/about');
    }

    /**
     * @inheritDoc
     */
    public function bootstrap(): void
    {
        require CONFIG_DIR . '/configuration.php';
    }
}

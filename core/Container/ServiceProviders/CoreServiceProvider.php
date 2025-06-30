<?php

declare(strict_types=1);

namespace Core\Container\ServiceProviders;

use Core\Clock\Clock;
use Core\Config\Config;
use Core\Config\ConfigInterface;
use Core\Container\Container;
use Core\Error\ErrorRenderer;
use Core\Error\ErrorRendererInterface;
use Core\Http\Middleware\ErrorHandlerMiddleware;
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
use Core\View\HtmlView;
use Core\View\JsonView;
use Core\View\ViewRenderer;
use Psr\Clock\ClockInterface;

final readonly class CoreServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container): void
    {
        $container->bindSingleton(ConfigInterface::class, Config::class);
        $container->bindSingleton(MiddlewareQueueInterface::class, MiddlewareQueue::class);
        $container->bindSingleton(RouterInterface::class, Router::class);

        $container->bind(RoutingMiddleware::class);
        $container->bind(ErrorHandlerMiddleware::class);
        $container->bind(RequestFactoryInterface::class, RequestFactory::class);
        $container->bind(HtmlView::class);
        $container->bind(HttpRunnerInterface::class, HttpRunner::class);
        $container->bind(RouteParserInterface::class, RouteParser::class);
        $container->bind(ClockInterface::class, Clock::class);
        $container->bind(ServerResponseRendererInterface::class, ServerResponseRenderer::class);
        $container->bind(ErrorRendererInterface::class, ErrorRenderer::class);
        $container->bind(JsonView::class);
        $container->bind(ViewRenderer::class);
    }
}

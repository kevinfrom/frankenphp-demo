<?php
declare(strict_types=1);

namespace Core\Http\Router;

use ArrayObject;
use Core\Config\Config;
use Core\Http\Exceptions\ServerErrors\InternalErrorException;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Request\Factory\RequestFactoryInterface;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Response\Rendering\ServerResponseRendererInterface;
use Core\Http\Route\RouteInterface;
use Core\Http\RouteParser\RouteParserInterface;
use Core\Http\Runner\HttpRunnerInterface;
use Throwable;

final readonly class Router implements RouterInterface
{
    /** @var ArrayObject<string, RequestHandlerInterface> */
    protected ArrayObject $routes;

    public function __construct(
        public RouteParserInterface            $parser,
        public HttpRunnerInterface             $runner,
        public RequestFactoryInterface         $factory,
        public MiddlewareQueueInterface        $middlewareQueue,
        public ServerResponseRendererInterface $renderer
    )
    {
        $this->routes = new ArrayObject();
    }

    /**
     * @inheritDoc
     */
    public function addRoute(RouteInterface $route): void
    {
        $path = $route->getPath();

        foreach ($route->getMethods() as $method) {
            $id = $this->parser->getRouteId($method, $path);
            $this->routes->offsetSet($id, $route);
        }
    }

    /**
     * @inheritDoc
     */
    public function getMiddlewareQueue(): MiddlewareQueueInterface
    {
        return $this->middlewareQueue;
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function dispatch(): never
    {
        try {
            $request = $this->factory->fromGlobals();
            $response = $this->runner->run($this->middlewareQueue, $request);
        } catch (Throwable $e) {
            if (Config::getInstance()->get('debug')) {
                throw $e;
            }

            $response = response(InternalErrorException::REASON_PHRASE, InternalErrorException::STATUS_CODE);
        }

        $this->renderer->render($response);
    }

    /**
     * @inheritDoc
     */
    public function match(string $method, string $path): ?RouteInterface
    {
        $routeId = $this->parser->getRouteId($method, $path);

        if ($this->routes->offsetExists($routeId)) {
            return $this->routes->offsetGet($routeId);
        }

        return null;
    }
}

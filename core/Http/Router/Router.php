<?php
declare(strict_types=1);

namespace Core\Http\Router;

use ArrayObject;
use Closure;
use Core\Http\Middleware\Queue\MiddlewareQueueInterface;
use Core\Http\Request\Factory\RequestFactoryInterface;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\Rendering\ServerResponseRendererInterface;
use Core\Http\Response\ServerResponseInterface;
use Core\Http\RouteParser\RouteParserInterface;
use Core\Http\Runner\HttpRunnerInterface;
use Throwable;
use function Core\config;
use function Core\redirect;
use function Core\response;

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
    public function addRoute(array $methods, string $path, callable $handler): void
    {
        foreach ($methods as $method) {
            $id = $this->parser->getRouteId($method, $path);
            $this->routes->offsetSet($id, $this->wrapHandler($handler));
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $path, callable $handler): void
    {
        $this->addRoute(['GET', 'HEAD'], $path, $handler);
    }

    /**
     * @inheritDoc
     */
    public function post(string $path, callable $handler): void
    {
        $this->addRoute(['POST'], $path, $handler);
    }

    /**
     * @inheritDoc
     */
    public function put(string $path, callable $handler): void
    {
        $this->addRoute(['PUT'], $path, $handler);
    }

    /**
     * @inheritDoc
     */
    public function patch(string $path, callable $handler): void
    {
        $this->addRoute(['PATCH'], $path, $handler);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path, callable $handler): void
    {
        $this->addRoute(['DELETE'], $path, $handler);
    }

    /**
     * @inheritDoc
     */
    public function redirect(string $path, string $to, int $statusCode = 302, array $methods = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE']): void
    {
        $this->addRoute($methods, $path, function () use ($to, $statusCode): ServerResponseInterface {
            return redirect($to, $statusCode);
        });
    }

    /**
     * Wrap handler in a RequestHandlerInterface.
     *
     * @param callable $handler
     * @return RequestHandlerInterface
     */
    protected function wrapHandler(callable $handler): RequestHandlerInterface
    {
        return new readonly class($handler) implements RequestHandlerInterface {
            public function __construct(protected Closure $handler)
            {
            }

            public function handle(ServerRequestInterface $request): ServerResponseInterface
            {
                return ($this->handler)($request);
            }
        };
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
            if (config()->get('debug')) {
                throw $e;
            }

            $response = response('Internal Server Error', 500);
        }

        $this->renderer->render($response);
    }

    /**
     * @inheritDoc
     */
    public function match(string $method, string $path): ?RequestHandlerInterface
    {
        $routeId = $this->parser->getRouteId($method, $path);

        if ($this->routes->offsetExists($routeId)) {
            return $this->routes->offsetGet($routeId);
        }

        return null;
    }
}

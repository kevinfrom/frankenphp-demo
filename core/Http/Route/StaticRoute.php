<?php
declare(strict_types=1);

namespace Core\Http\Route;

use Closure;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;

final readonly class StaticRoute implements RouteInterface
{
    public function __construct(
        protected array $methods,
        protected string $path,
        protected Closure $handler
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ServerResponseInterface
    {
        return call_user_func($this->getHandler(), $request);
    }
}

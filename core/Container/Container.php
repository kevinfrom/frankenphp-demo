<?php
declare(strict_types=1);

namespace Core\Container;

use ArrayObject;
use Psr\Container\ContainerInterface;

final readonly class Container implements ContainerInterface
{
    /** @var ArrayObject<class-string, class-string> */
    protected ArrayObject $bindings;

    /** @var ArrayObject<class-string, object> */
    protected ArrayObject $singletons;

    public function __construct()
    {
        $this->bindings = new ArrayObject();
        $this->singletons = new ArrayObject();
    }

    /**
     * Bind a service to the container.
     *
     * @param class-string|string $id The ID to bind the service to. Can be a class name or a string identifier.
     * @param null|class-string|callable $concrete The concrete implementation or a callable that returns the service.
     * @param bool $singleton Whether to bind the service as a singleton.
     *
     * @return void
     * @throws ContainerException
     */
    public function bind(string $id, null|string|callable $concrete = null, bool $singleton = false): void
    {
        if ($this->has($id)) {
            throw new ContainerException("A resolver for ID is already bound: $id");
        }

        $concrete ??= $id;

        if ($singleton) {
            $concrete = function () use ($id, $concrete) {
                if (!$this->singletons->offsetExists($id)) {
                    $this->singletons->offsetSet($id, is_callable($concrete) ? $concrete($this) : new $concrete);
                }

                $this->bindings->offsetUnset($id);

                return $this->singletons->offsetGet($id);
            };
        }

        $this->bindings->offsetSet($id, $concrete);
    }

    /**
     * Get a service from the container by its ID.
     *
     * @template T
     *
     * @param class-string<T> $id
     *
     * @return T
     * @throws NotFoundException Thrown if the service is not found in the container.
     * @throws ContainerException Thrown if the service could not be resolved.
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException("Service not found: $id");
        }

        if ($this->singletons->offsetExists($id)) {
            return $this->singletons->offsetGet($id);
        }

        if ($this->bindings->offsetExists($id)) {
            $concrete = $this->bindings->offsetGet($id);

            if (is_callable($concrete)) {
                return $concrete($this);
            }

            return new $concrete;
        }

        throw new ContainerException("Service not found: $id");
    }

    /**
     * Check if a service is bound in the container.
     *
     * @param string $id The ID of the service to check.
     *
     * @return bool True if the service is bound, false otherwise.
     */
    public function has(string $id): bool
    {
        if ($this->singletons->offsetExists($id)) {
            return true;
        }

        return $this->bindings->offsetExists($id);
    }
}

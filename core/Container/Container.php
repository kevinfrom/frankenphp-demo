<?php

declare(strict_types=1);

namespace Core\Container;

use ArrayObject;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use ReflectionUnionType;

final class Container implements ContainerInterface
{
    protected static ?self $instance = null;

    /** @var ArrayObject<class-string, null|class-string|callable> */
    protected readonly ArrayObject $bindings;

    /** @var ArrayObject<class-string, object> */
    protected readonly ArrayObject $singletons;

    protected function __construct()
    {
        $this->bindings   = new ArrayObject();
        $this->singletons = new ArrayObject();
    }

    /**
     * Get the singleton instance of the container.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Bind a service to the container.
     *
     * @param class-string|string        $id The ID to bind the service to. Can be a class name or a string identifier.
     * @param null|class-string|callable $concrete The concrete implementation or a callable that returns the service.
     *
     * @return void
     */
    public function bind(string $id, null|string|callable $concrete = null): void
    {
        $this->bindings->offsetSet($id, $concrete ?? $id);
    }

    /**
     * Bind a service as a singleton in the container.
     *
     * @param string               $id The ID to bind the service to. Can be a class name or a string identifier.
     * @param string|callable|null $concrete The concrete implementation or a callable that returns the service.
     *
     * @return void
     */
    public function bindSingleton(string $id, null|string|callable $concrete = null): void
    {
        $this->bind($id, function () use ($id, $concrete) {
            $this->singletons->offsetSet($id, $this->get($concrete ?? $id));

            return $this->singletons->offsetGet($id);
        });
    }

    /**
     * Get a service from the container by its ID.
     *
     * @template TClass
     *
     * @param string|class-string<TClass> $id
     *
     * @return TClass
     * @throws ReflectionException If the class does not exist.
     * @throws ContainerException Thrown if the service could not be resolved.
     */
    public function get(string $id)
    {
        if ($id === self::class) {
            return $this;
        }

        if ($this->has($id)) {
            $concrete = $this->bindings->offsetGet($id);

            if ($this->singletons->offsetExists($id)) {
                $concrete = $this->singletons->offsetGet($id);
            }

            if (is_callable($concrete)) {
                return $concrete($this);
            }

            if (is_object($concrete)) {
                return $concrete;
            }

            return $this->resolve($concrete);
        }

        return $this->resolve($id);
    }

    /**
     * Resolve a service by its ID.
     *
     * @template TClass
     *
     * @param class-string<TClass> $id
     *
     * @return TClass
     * @throws ReflectionException If the class does not exist.
     * @throws ContainerException Thrown if the service could not be resolved.
     */
    protected function resolve(string $id): object
    {
        $reflection = new ReflectionClass($id);

        if (!$reflection->isInstantiable()) {
            throw new ContainerException("Cannot resolve service, class is not instantiable: $id");
        }

        $constructor = $reflection->getConstructor();

        if (!$constructor || !$constructor->getNumberOfParameters()) {
            return new $id();
        }

        $dependencies = array_map(function (ReflectionParameter $parameter) {
            $type = $parameter->getType();
            $name = $parameter->getName();

            if (!$type) {
                throw new ContainerException("Cannot resolve service, parameter is not a class: $name");
            }

            if ($type instanceof ReflectionUnionType) {
                foreach ($type->getTypes() as $unionType) {
                    if (!$unionType->isBuiltin()) {
                        return $this->get($unionType->getName());
                    }
                }
            }

            if ($type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    return $parameter->getDefaultValue();
                }

                throw new ContainerException("Cannot resolve service, parameter is a built-in type: $name");
            }

            return $this->get($type->getName());
        }, $constructor->getParameters());

        return $reflection->newInstanceArgs($dependencies);
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

<?php

declare(strict_types=1);

namespace Core\Config;

use ArrayObject;
use Core\Utility\Hash;

final class Config implements ConfigInterface
{
    protected static ?self $instance = null;

    protected readonly ArrayObject $config;

    public function __construct()
    {
        $this->config = new ArrayObject();
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): array
    {
        return $this->config->getArrayCopy();
    }

    /**
     * @inheritDoc
     */
    public function get(string $key): mixed
    {
        if ($this->has($key)) {
            return Hash::get($this->getConfig(), $key);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function set(string|array $key, mixed $value = null): void
    {
        if (is_array($key)) {
            $this->config->exchangeArray(array_merge($this->getConfig(), $key));

            return;
        }

        $this->config->exchangeArray(Hash::set($this->getConfig(), $key, $value));
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return Hash::exists($this->getConfig(), $key);
    }
}

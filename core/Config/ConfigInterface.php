<?php

declare(strict_types=1);

namespace Core\Config;

interface ConfigInterface
{
    /**
     * Get the entire configuration array.
     *
     * @return array
     */
    public function getConfig(): array;

    /**
     * Gets a configuration value by its key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Sets a configuration value.
     *
     * @param array|string $key If an array is provided, it will be merged with the existing configuration.
     * @param null|mixed   $value
     *
     * @return void
     */
    public function set(string|array $key, mixed $value = null): void;

    /**
     * Checks if a configuration key exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;
}

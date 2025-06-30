<?php

declare(strict_types=1);

namespace Core\Utility;

final readonly class Hash
{
    /**
     * Get a value from an array using a key.
     *
     * @param array  $data The array to search in.
     * @param string $key The key to search for. Dot notation is supported (e.g., 'key.subkey').
     *
     * @return mixed
     */
    public static function get(array $data, string $key): mixed
    {
        $keys = explode('.', $key);
        $path = $data;

        while ($keys) {
            $key = array_shift($keys);

            if (!isset($path[$key])) {
                return null;
            }

            $path = $path[$key];
        }

        return $path;
    }

    /**
     * Set a value in an array using a key.
     *
     * @param array  $data The array to modify.
     * @param string $key The key to set. Dot notation is supported (e.g., 'key.subkey').
     * @param mixed  $value The value to set.
     *
     * @return array The modified array.
     */
    public static function set(array $data, string $key, mixed $value): array
    {
        $keys = explode('.', $key);
        $path = &$data;

        while ($keys) {
            $key = array_shift($keys);

            if (!isset($path[$key]) || !is_array($path[$key])) {
                $path[$key] = [];
            }

            if (empty($keys)) {
                $path[$key] = $value;
            } else {
                $path = &$path[$key];
            }
        }

        return $data;
    }

    /**
     * Unset a value in an array using a key.
     *
     * @param array  $data The array to modify.
     * @param string $key The key to unset. Dot notation is supported (e.g., 'key.subkey').
     *
     * @return array The modified array.
     */
    public static function unset(array $data, string $key): array
    {
        $keys = explode('.', $key);
        $path = &$data;

        while ($keys) {
            $key = array_shift($keys);

            if (!isset($path[$key])) {
                break;
            }

            if (empty($keys)) {
                unset($path[$key]);
                break;
            }

            $path = &$path[$key];
        }

        return $data;
    }

    public static function exists(array $data, string $key): bool
    {
        $keys = explode('.', $key);
        $path = $data;

        while ($keys) {
            $key = array_shift($keys);

            if (!isset($path[$key])) {
                return false;
            }

            $path = $path[$key];
        }

        return true;
    }
}

<?php
declare(strict_types=1);

namespace Core;

use Core\Config\Config;
use Core\Config\ConfigInterface;
use Core\Container\Container;
use Core\Container\ContainerException;
use Core\Container\NotFoundException;
use Core\Http\Response\ServerResponse;
use Core\Http\Response\ServerResponseInterface;
use Core\View\HtmlView;
use Core\View\ViewInterface;

/**
 * Get an environment variable.
 *
 * @param string $key
 *
 * @return string|bool|int|float|null
 */
function env(string $key): string|bool|int|float|null
{
    $value = getenv($key) ?? $_ENV[$key] ?? null;

    if (!$value && file_exists(ROOT_DIR . DS . '.env')) {
        $lines = file(ROOT_DIR . DS . '.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            if (str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$envKey, $envValue] = explode('=', $line, 2);

            // Remove any surrounding quotes from the value
            $envValue = preg_replace('/["\']+/', '', $envValue);

            putenv("$envKey=$envValue");

            if ($envKey === $key) {
                $value = $envValue;
            }
        }
    }

    // Parse boolean values
    if (in_array(mb_strtolower((string)$value), ['true', 'false'])) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    // Parse numeric values into floats and integers
    if (is_numeric($value)) {
        if (str_contains($value, '.')) {
            $value = filter_var($value, FILTER_VALIDATE_FLOAT);
        } else {
            $value = filter_var($value, FILTER_VALIDATE_INT);
        }
    }

    // Parse NULL-like values
    if ($value === '' || mb_strtolower((string)$value) === 'null') {
        $value = null;
    }

    return $value;
}

/**
 * Sanitize HTML input to prevent XSS attacks.
 *
 * @param string $html HTML to sanitize
 *
 * @return string The sanitized HTML
 */
function sanitize(string $html): string
{
    $encoding = mb_internal_encoding() ?: 'UTF-8';

    return htmlspecialchars($html, ENT_QUOTES | ENT_SUBSTITUTE, $encoding);
}

/**
 * Get the service container.
 *
 * @return Container
 */
function container(): Container
{
    return Container::getInstance();
}

/**
 * Get the configuration.
 *
 * @return ConfigInterface
 */
function config(): ConfigInterface
{
    return Config::getInstance();
}

/**
 * Create an HTML response.
 *
 * @param string|ViewInterface $body
 * @param int $statusCode
 * @param array<string, string|int|float|null> $headers
 *
 * @return ServerResponseInterface
 */
function response(string|ViewInterface $body, int $statusCode = 200, array $headers = []): ServerResponseInterface
{
    return new ServerResponse($body, $statusCode, $headers);
}

/**
 * Create an HTML view.
 *
 * @param string $template
 * @param array $data
 * @param string $layout
 *
 * @return ServerResponseInterface
 * @throws NotFoundException
 * @throws ContainerException
 */
function view(string $template, array $data = [], string $layout = 'default'): ServerResponseInterface
{
    $view = container()->get(HtmlView::class);
    $view->template = $template;
    $view->layout = $layout;
    $view->setData($data);

    return new ServerResponse($view);
}

/**
 * Create a redirect response.
 *
 * @param string $url
 * @param int $statusCode
 *
 * @return ServerResponseInterface
 */
function redirect(string $url, int $statusCode = 302): ServerResponseInterface
{
    return new ServerResponse('', $statusCode, [
        'Location' => $url,
    ]);
}

<?php
declare(strict_types=1);

use Core\Config\Config;
use Core\Config\ConfigInterface;
use Core\Http\Response\ServerResponse;
use Core\Http\Response\ServerResponseInterface;
use Core\View\ViewInterface;

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

<?php
declare(strict_types=1);

use Core\Http\Response\ServerResponse;
use Core\Http\Response\ServerResponseInterface;

/**
 * Create an HTML response.
 *
 * @param string $body
 * @param int    $statusCode
 *
 * @return ServerResponseInterface
 */
function response(string $body, int $statusCode = 200): ServerResponseInterface
{
    return new ServerResponse($body, $statusCode, [
        'Content-Type' => 'text/html; charset=UTF-8',
    ]);
}

/**
 * Create a redirect response.
 *
 * @param string $url
 * @param int    $statusCode
 *
 * @return ServerResponseInterface
 */
function redirect(string $url, int $statusCode = 302): ServerResponseInterface
{
    return new ServerResponse('', $statusCode, [
        'Location' => $url,
    ]);
}

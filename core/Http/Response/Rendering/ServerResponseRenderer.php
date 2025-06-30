<?php

declare(strict_types=1);

namespace Core\Http\Response\Rendering;

use Core\Http\Response\ServerResponseInterface;

final readonly class ServerResponseRenderer implements ServerResponseRendererInterface
{
    /**
     * @inheritDoc
     */
    public function render(ServerResponseInterface $response): string
    {
        if (extension_loaded('frankenphp')) {
            headers_send(103);
        }

        $statusCode = $response->getStatusCode();

        foreach ($response->getHeaders() as $name => $value) {
            header("$name: $value", true, $statusCode);
        }

        $body = $response->getStringBody();

        if (!$response->getHeaderLine('Content-Length')) {
            header('Content-Length: ' . strlen($body), true, $statusCode);
        }

        return $body;
    }
}

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
        http_response_code($response->getStatusCode());

        foreach ($response->getHeaders() as $name => $value) {
            header("$name: $value");
        }

        return $response->getStringBody();
    }
}

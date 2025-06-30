<?php

declare(strict_types=1);

namespace Core\Http\Response\Rendering;

use Core\Http\Response\ServerResponseInterface;

interface ServerResponseRendererInterface
{
    /**
     * Render and send the response to the client, closing the connection.
     *
     * @param ServerResponseInterface $response
     *
     * @return string
     */
    public function render(ServerResponseInterface $response): string;
}

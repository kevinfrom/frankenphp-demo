<?php
declare(strict_types=1);

namespace Core\Http\Request;

use Core\Http\Response\ServerResponseInterface;

interface RequestHandlerInterface
{
    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ServerResponseInterface
     */
    public function handle(ServerRequestInterface $request): ServerResponseInterface;
}

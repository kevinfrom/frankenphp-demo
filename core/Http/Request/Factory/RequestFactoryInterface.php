<?php

declare(strict_types=1);

namespace Core\Http\Request\Factory;

use Core\Http\Request\ServerRequestInterface;

interface RequestFactoryInterface
{
    /**
     * Create a new ServerRequestInterface instance from the global PHP variables.
     *
     * @return ServerRequestInterface
     */
    public function fromGlobals(): ServerRequestInterface;
}

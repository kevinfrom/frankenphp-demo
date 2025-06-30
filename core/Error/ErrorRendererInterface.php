<?php

declare(strict_types=1);

namespace Core\Error;

use Core\Http\Exceptions\HttpException;
use Core\Http\Response\ServerResponseInterface;
use Throwable;

interface ErrorRendererInterface
{
    /**
     * Renders an exception to a response.
     *
     * @param HttpException|Throwable $exception
     *
     * @return ServerResponseInterface
     */
    public function renderException(HttpException|Throwable $exception): ServerResponseInterface;
}

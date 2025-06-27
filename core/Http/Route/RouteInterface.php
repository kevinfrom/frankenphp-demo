<?php
declare(strict_types=1);

namespace Core\Http\Route;

use Core\Http\Request\RequestHandlerInterface;

interface RouteInterface extends RequestHandlerInterface
{
    public const string GET = 'GET';
    public const string HEAD = 'HEAD';
    public const string POST = 'POST';
    public const string PUT = 'PUT';
    public const string PATCH = 'PATCH';
    public const string DELETE = 'DELETE';

    /**
     * Get the HTTP methods that this route responds to.
     *
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * Get the route path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Get the handler for this route.
     *
     * @return callable
     */
    public function getHandler(): callable;
}

<?php

declare(strict_types=1);

namespace Core\Http\RouteParser;

interface RouteParserInterface
{
    /**
     * Get route ID for the given method and path.
     *
     * @param string $method
     * @param string $path
     *
     * @return string
     */
    public function getRouteId(string $method, string $path): string;

    /**
     * Normalize HTTP method.
     *
     * @param string $method
     *
     * @return string
     */
    public function normalizeMethod(string $method): string;

    /**
     * Normalize path.
     *
     * @param string $path
     *
     * @return string
     */
    public function normalizePath(string $path): string;

    /**
     * Get route ID for the current route based on the request method and URI.
     *
     * @return string
     */
    public function getRouteIdForCurrentRoute(): string;
}

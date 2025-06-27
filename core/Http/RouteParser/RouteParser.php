<?php
declare(strict_types=1);

namespace Core\Http\RouteParser;

final readonly class RouteParser implements RouteParserInterface
{
    /**
     * @inheritDoc
     */
    public function getRouteId(string $method, string $path): string
    {
        $method = $this->normalizeMethod($method);
        $path   = $this->normalizePath($path);

        return "$method:$path";
    }

    /**
     * Normalize HTTP method.
     *
     * @param string $method
     *
     * @return string
     */
    public function normalizeMethod(string $method): string
    {
        return trim(mb_strtoupper($method));
    }

    /**
     * Normalize path.
     *
     * @param string $path
     *
     * @return string
     */
    public function normalizePath(string $path): string
    {
        $path = trim(mb_strtolower($path));

        return '/' . trim($path, '/');
    }

    /**
     * @inheritDoc
     */
    public function getRouteIdForCurrentRoute(): string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path   = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);

        return $this->getRouteId($method, $path);
    }
}

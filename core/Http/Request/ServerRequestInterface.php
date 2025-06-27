<?php
declare(strict_types=1);

namespace Core\Http\Request;

interface ServerRequestInterface
{
    /**
     * Get the request headers.
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get a specific header value by name.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getHeaderLine(string $name): ?string;

    /**
     * Get the HTTP method of the request.
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get the request path.
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Get the request query parameters.
     *
     * @return array
     */
    public function getQueryParams(): array;

    /**
     * Get parsed body parameters from the request.
     *
     * @return array
     */
    public function getParsedBody(): array;
}

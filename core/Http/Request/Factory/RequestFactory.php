<?php

declare(strict_types=1);

namespace Core\Http\Request\Factory;

use Core\Http\Exceptions\ClientErrors\BadRequestException;
use Core\Http\Request\ServerRequest;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\RouteParser\RouteParserInterface;

final readonly class RequestFactory implements RequestFactoryInterface
{
    public function __construct(public RouteParserInterface $routeParser)
    {
    }

    /**
     * @inheritDoc
     * @throws BadRequestException
     */
    public function fromGlobals(): ServerRequestInterface
    {
        $method      = $this->parseMethod();
        $path        = $this->parsePath();
        $queryParams = $this->parseQueryParams();
        $headers     = $this->parseHeaders();
        $parsedBody  = $this->parseBody();

        return new ServerRequest($method, $path, $queryParams, $headers, $parsedBody);
    }

    /**
     * Parse the HTTP method from the server variables.
     *
     * @return string
     */
    protected function parseMethod(): string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        return $this->routeParser->normalizeMethod($method);
    }

    /**
     * Parse the request path from the server variables.
     *
     * @return string
     * @throws BadRequestException
     */
    protected function parsePath(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        if (!$path) {
            throw new BadRequestException('Invalid request path');
        }

        return $this->routeParser->normalizePath($path);
    }

    /**
     * Parse query parameters from the request.
     *
     * @return array
     */
    protected function parseQueryParams(): array
    {
        return $_GET ?? [];
    }

    /**
     * Parse headers from the request.
     *
     * @return array
     */
    protected function parseHeaders(): array
    {
        return getallheaders() ?? [];
    }

    /**
     * Parse the request body.
     *
     * @return array
     */
    protected function parseBody(): array
    {
        if (!empty($_POST)) {
            return $_POST;
        }

        $body = file_get_contents('php://input');

        if ($body === false) {
            return [];
        }

        return json_decode($body, true, JSON_THROW_ON_ERROR) ?: [];
    }
}

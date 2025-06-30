<?php

declare(strict_types=1);

namespace Core\Http\Request;

final readonly class ServerRequest implements ServerRequestInterface
{
    public function __construct(
        protected string $method,
        protected string $path,
        protected array $queryParams = [],
        protected array $headers = [],
        protected array $parsedBody = []
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody(): array
    {
        return $this->parsedBody;
    }
}

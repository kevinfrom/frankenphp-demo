<?php

declare(strict_types=1);

namespace Core\Http\Response;

use Core\View\ViewInterface;

interface ServerResponseInterface
{
    /**
     * Get the HTTP status code of the response.
     *
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * Set the HTTP status code for the response.
     *
     * @param int $code
     *
     * @return void
     */
    public function setStatusCode(int $code): void;

    /**
     * Get the reason phrase associated with the HTTP status code.
     *
     * @return string
     */
    public function getReasonPhrase(): string;

    /**
     * Get the headers of the response.
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     * Get a specific header value from the response.
     *
     * @param string $name
     *
     * @return null|string
     */
    public function getHeaderLine(string $name): ?string;

    /**
     * Set a header for the response.
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setHeader(string $name, string $value): void;

    /**
     * Unset a header from the response.
     *
     * @param string $name
     *
     * @return void
     */
    public function unsetHeader(string $name): void;

    /**
     * Get content type of the response.
     *
     * @return string
     */
    public function getContentType(): string;

    /**
     * Set the content type of the response.
     *
     * @param string $contentType
     *
     * @return void
     */
    public function setContentType(string $contentType): void;

    /**
     * Get string representation of the response body.
     *
     * @return string
     */
    public function getStringBody(): string;

    /**
     * Set the body of the response.
     *
     * @param string|ViewInterface $body
     *
     * @return void
     */
    public function setBody(string|ViewInterface $body): void;

    /**
     * Set the response body as a string.
     *
     * @param string $body
     *
     * @return void
     */
    public function setStringBody(string $body): void;
}

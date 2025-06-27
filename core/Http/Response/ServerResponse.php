<?php
declare(strict_types=1);

namespace Core\Http\Response;

use ArrayObject;
use Core\Http\Exceptions\ClientErrors\BadRequestException;
use Core\Http\Exceptions\ClientErrors\ForbiddenException;
use Core\Http\Exceptions\ClientErrors\MethodNotAllowedException;
use Core\Http\Exceptions\ClientErrors\NotAcceptableException;
use Core\Http\Exceptions\ClientErrors\NotFoundException;
use Core\Http\Exceptions\ClientErrors\RequestTimeoutException;
use Core\Http\Exceptions\ClientErrors\TooManyRequestsException;
use Core\Http\Exceptions\ClientErrors\UnauthorizedException;
use Core\Http\Exceptions\ServerErrors\BadGatewayException;
use Core\Http\Exceptions\ServerErrors\GatewayTimeoutException;
use Core\Http\Exceptions\ServerErrors\InternalErrorException;
use Core\Http\Exceptions\ServerErrors\NotImplementedException;
use Core\Http\Exceptions\ServerErrors\ServiceUnavailableException;

final class ServerResponse implements ServerResponseInterface
{
    protected ArrayObject $headers;

    public function __construct(
        protected string $body = '',
        protected int $statusCode = 200,
        array $headers = []
    ) {
        $this->headers = new ArrayObject($headers);

        if (!$this->getHeaderLine('Content-Type')) {
            $this->setHeader('Content-Type', 'text/html; charset=UTF-8');
        }
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @inheritDoc
     */
    public function setStatusCode(int $code): void
    {
        $this->statusCode = $code;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return match ($this->statusCode) {
            // Informational responses
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            103 => 'Early Hints',

            // Successful responses
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',

            // Redirection responses
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect',

            // Client error responses
            BadRequestException::STATUS_CODE => BadRequestException::REASON_PHRASE,
            UnauthorizedException::STATUS_CODE => UnauthorizedException::REASON_PHRASE,
            ForbiddenException::STATUS_CODE => ForbiddenException::REASON_PHRASE,
            NotFoundException::STATUS_CODE => NotFoundException::REASON_PHRASE,
            MethodNotAllowedException::STATUS_CODE => MethodNotAllowedException::REASON_PHRASE,
            NotAcceptableException::STATUS_CODE => NotAcceptableException::REASON_PHRASE,
            RequestTimeoutException::STATUS_CODE => RequestTimeoutException::REASON_PHRASE,
            TooManyRequestsException::STATUS_CODE => TooManyRequestsException::REASON_PHRASE,

            // Server error responses
            InternalErrorException::STATUS_CODE => InternalErrorException::REASON_PHRASE,
            NotImplementedException::STATUS_CODE => NotImplementedException::REASON_PHRASE,
            BadGatewayException::STATUS_CODE => BadGatewayException::REASON_PHRASE,
            ServiceUnavailableException::STATUS_CODE => ServiceUnavailableException::REASON_PHRASE,
            GatewayTimeoutException::STATUS_CODE => GatewayTimeoutException::REASON_PHRASE,
        };
    }

    /**
     * Normalize HTTP header name
     *
     * @param string $name
     *
     * @return string
     */
    protected function normalizeHeaderName(string $name): string
    {
        return mb_strtolower(trim($name));
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->headers->getArrayCopy();
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine(string $name): ?string
    {
        $name = $this->normalizeHeaderName($name);

        if ($this->headers->offsetExists($name)) {
            return $this->headers->offsetGet($name);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function setHeader(string $name, string $value): void
    {
        $name = $this->normalizeHeaderName($name);
        $this->headers->offsetSet($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function unsetHeader(string $name): void
    {
        $name = $this->normalizeHeaderName($name);
        $this->headers->offsetUnset($name);
    }

    /**
     * @inheritDoc
     */
    public function getStringBody(): string
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function setStringBody(string $body): void
    {
        $this->body = $body;
    }
}

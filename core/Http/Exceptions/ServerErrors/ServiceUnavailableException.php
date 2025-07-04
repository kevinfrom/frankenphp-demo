<?php

declare(strict_types=1);

namespace Core\Http\Exceptions\ServerErrors;

use Core\Http\Exceptions\HttpException;

final class ServiceUnavailableException extends HttpException
{
    public const int STATUS_CODE = 503;
    public const string REASON_PHRASE = 'Service Unavailable';

    public function __construct(string $message = self::REASON_PHRASE)
    {
        parent::__construct($message, self::STATUS_CODE);
    }
}

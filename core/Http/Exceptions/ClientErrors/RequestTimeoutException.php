<?php
declare(strict_types=1);

namespace Core\Http\Exceptions\ClientErrors;

use Core\Http\Exceptions\HttpException;

final class RequestTimeoutException extends HttpException
{
    public const int STATUS_CODE = 408;
    public const string REASON_PHRASE = 'Request Timeout';

    public function __construct(string $message = self::REASON_PHRASE)
    {
        parent::__construct($message, self::STATUS_CODE);
    }
}

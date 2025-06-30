<?php

declare(strict_types=1);

namespace Core\Http\Exceptions\ClientErrors;

use Core\Http\Exceptions\HttpException;

final class UnauthorizedException extends HttpException
{
    public const int STATUS_CODE = 401;
    public const string REASON_PHRASE = 'Unauthorized';

    public function __construct(string $message = self::REASON_PHRASE)
    {
        parent::__construct($message, self::STATUS_CODE);
    }
}

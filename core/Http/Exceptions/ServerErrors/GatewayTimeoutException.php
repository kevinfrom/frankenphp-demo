<?php
declare(strict_types=1);

namespace Core\Http\Exceptions\ServerErrors;

use Core\Http\Exceptions\HttpException;

final  class GatewayTimeoutException extends HttpException
{
    public const int STATUS_CODE = 504;
    public const string REASON_PHRASE = 'Gateway Timeout';

    public function __construct(string $message = self::REASON_PHRASE)
    {
        parent::__construct($message, self::STATUS_CODE);
    }
}

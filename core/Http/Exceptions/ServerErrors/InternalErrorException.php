<?php
declare(strict_types=1);

namespace Core\Http\Exceptions\ServerErrors;

use Core\Http\Exceptions\HttpException;

final  class InternalErrorException extends HttpException
{
    public const int STATUS_CODE = 500;
    public const string REASON_PHRASE = 'Internal Server Error';

    public function __construct(string $message = self::REASON_PHRASE)
    {
        parent::__construct($message, self::STATUS_CODE);
    }
}

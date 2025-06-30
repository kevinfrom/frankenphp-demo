<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Http\Exceptions\ClientErrors\NotFoundException;
use Core\Http\Exceptions\ServerErrors\InternalErrorException;

final readonly class ErrorController
{
    /**
     * @throws NotFoundException
     */
    public function throw404(): void
    {
        throw new NotFoundException('Test 404 exception');
    }

    /**
     * @throws InternalErrorException
     */
    public function throw500(): void
    {
        throw new InternalErrorException('Test 500 exception');
    }
}

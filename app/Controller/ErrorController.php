<?php

declare(strict_types=1);

namespace App\Controller;

use Core\Http\Exceptions\ClientErrors\NotFoundException;
use Core\Http\Exceptions\ServerErrors\InternalErrorException;
use function Core\preloadAsset;

final readonly class ErrorController
{
    /**
     * @throws NotFoundException
     */
    public function throw404(): never
    {
        throw new NotFoundException('Test 404 exception');
    }

    /**
     * @throws InternalErrorException
     */
    public function throw500(): never
    {
        preloadAsset('https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4', 'script');
        throw new InternalErrorException('Test 500 exception');
    }
}

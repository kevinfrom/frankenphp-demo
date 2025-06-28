<?php
declare(strict_types=1);

namespace App\Middleware;

use Core\Http\Exceptions\ServerErrors\InternalErrorException;
use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;
use function Core\config;

final readonly class AppNameMiddleware implements MiddlewareInterface
{
    /**
     * @inheritDoc
     * @throws InternalErrorException
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $next): ServerResponseInterface
    {
        $appName = config()->get('App.name');

        if (!$appName) {
            throw new InternalErrorException('Application name is not configured.');
        }

        $response = $next->handle($request);
        $response->setHeader('X-App-Name', $appName);

        return $response;
    }
}

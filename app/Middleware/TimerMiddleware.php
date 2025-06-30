<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\Http\Middleware\MiddlewareInterface;
use Core\Http\Request\RequestHandlerInterface;
use Core\Http\Request\ServerRequestInterface;
use Core\Http\Response\ServerResponseInterface;
use Psr\Clock\ClockInterface;

final readonly class TimerMiddleware implements MiddlewareInterface
{
    public function __construct(public ClockInterface $clock)
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request, RequestHandlerInterface $next): ServerResponseInterface
    {
        $start    = $this->clock->now();
        $response = $next->handle($request);
        $end      = $this->clock->now();

        $duration = $end->diff($start);
        $response->setHeader('X-Request-Duration', $duration->format('%s.%f s'));

        return $response;
    }
}

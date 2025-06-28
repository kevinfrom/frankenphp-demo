<?php
declare(strict_types=1);

namespace App;

use App\Middleware\TimerMiddleware;
use Core\Container\ServiceProviderInterface;
use Psr\Clock\ClockInterface;
use Psr\Container\ContainerInterface;

final readonly class AppServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(ContainerInterface $container): void
    {
        $container->bind(TimerMiddleware::class, function (ContainerInterface $container): TimerMiddleware {
            $clock = $container->get(ClockInterface::class);

            return new TimerMiddleware($clock);
        });
    }
}

<?php
declare(strict_types=1);

namespace Core\Container\ServiceProviders;

use Core\Container\Container;

interface ServiceProviderInterface
{
    /**
     * Register services in the container.
     *
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void;
}

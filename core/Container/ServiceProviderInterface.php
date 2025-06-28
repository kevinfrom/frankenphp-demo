<?php
declare(strict_types=1);

namespace Core\Container;

use Psr\Container\ContainerInterface;

interface ServiceProviderInterface
{
    /**
     * Register services in the container.
     *
     * @param ContainerInterface $container
     * @return void
     */
    public function register(ContainerInterface $container): void;
}

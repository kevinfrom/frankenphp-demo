<?php
declare(strict_types=1);

namespace App;

use Core\Http\Router\RouterInterface;
use Psr\Container\ContainerInterface;

final readonly class Application
{
    public function __construct(
        public ContainerInterface $container,
        public RouterInterface $router
    ) {
    }

    public function run(): never
    {
        $this->router->dispatch();
    }
}

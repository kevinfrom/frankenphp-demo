<?php
declare(strict_types=1);

namespace Core\Container;

use Psr\Container\ContainerExceptionInterface;

final class ContainerException extends \Exception implements ContainerExceptionInterface
{
}

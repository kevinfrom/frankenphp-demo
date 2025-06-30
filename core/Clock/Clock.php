<?php

declare(strict_types=1);

namespace Core\Clock;

use DateTimeImmutable;
use Psr\Clock\ClockInterface;

final readonly class Clock implements ClockInterface
{
    /**
     * @inheritDoc
     */
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}

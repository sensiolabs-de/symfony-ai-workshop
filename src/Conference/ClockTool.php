<?php

namespace App\Conference;

use Psr\Clock\ClockInterface;
use Symfony\AI\Agent\Toolbox\Attribute\AsTool;

#[AsTool('clock', 'Returns the current date and time.')]
final readonly class ClockTool
{
    public function __construct(
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(): string
    {
        return $this->clock->now()
            ->setTimezone(new \DateTimeZone('Europe/Warsaw'))
            ->format(\DATE_ATOM);
    }
}

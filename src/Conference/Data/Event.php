<?php

namespace App\Conference\Data;

use Symfony\AI\Store\Document\DocumentInterface;
use Symfony\AI\Store\Document\Metadata;
use Symfony\Component\Uid\Uuid;

class Event implements DocumentInterface
{
    private const UUID_NAMESPACE = 'd4b5f6e2-3c1a-4f7e-9b2a-8e5f6d7c8a9b';

    public function __construct(
        private readonly string $title,
        private readonly TimeSpan $timeSpan,
        private readonly Slot $slot,
    ) {
    }

    public function getId(): string
    {
        return (string) Uuid::v5(Uuid::fromString(self::UUID_NAMESPACE), $this->title.$this->timeSpan->toString());
    }

    public function getContent(): string
    {
        return <<<EOD
            Title: {$this->title}
            Time Span: {$this->timeSpan->getStart()->format('Y-m-d H:i:s')} to {$this->timeSpan->getEnd()->format('Y-m-d H:i:s')}
            EOD;
    }

    public function getMetadata(): Metadata
    {
        return new Metadata([
            'title' => $this->title,
            'start_time' => $this->timeSpan->getStart()->format('Y-m-d H:i:s'),
            'end_time' => $this->timeSpan->getEnd()->format('Y-m-d H:i:s'),
        ]);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getTimeSpan(): TimeSpan
    {
        return $this->timeSpan;
    }

    public function getSlot(): Slot
    {
        return $this->slot;
    }
}

<?php

namespace App\Conference\Data;

use Symfony\AI\Store\Document\Metadata;

class Talk extends Event
{
    public function __construct(
        string $title,
        private readonly string $speaker,
        private readonly string $description,
        TimeSpan $timeSpan,
        Slot $slot,
    ) {
        parent::__construct($title, $timeSpan, $slot);
    }

    public function getContent(): string
    {
        return <<<EOD
            Title: {$this->getTitle()}
            Speaker: {$this->speaker}
            Description: {$this->description}
            Time Span: {$this->getTimeSpan()->getStart()->format('Y-m-d H:i:s')} to {$this->getTimeSpan()->getEnd()->format('Y-m-d H:i:s')}
            EOD;
    }

    public function getMetadata(): Metadata
    {
        return new Metadata([
            ...parent::getMetadata()->getArrayCopy(),
            'speaker' => $this->speaker,
            'description' => $this->description,
        ]);
    }

    public function getSpeaker(): string
    {
        return $this->speaker;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isOver(\DateTimeImmutable $now): bool
    {
        return $now > $this->getTimeSpan()->getEnd();
    }
}

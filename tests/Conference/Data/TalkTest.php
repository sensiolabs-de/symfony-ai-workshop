<?php

namespace App\Tests\Conference\Data;

use App\Conference\Data\Slot;
use App\Conference\Data\Talk;
use App\Conference\Data\TimeSpan;
use PHPUnit\Framework\TestCase;
use Symfony\AI\Store\Document\DocumentInterface;

final class TalkTest extends TestCase
{
    public function testDeterministicUuidGeneration(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Talk::class, DocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement DocumentInterface');
        }

        $talkA = $this->createTalk('My Talk', 'Alice', 'About stuff', '2025-01-10 09:00:00', '2025-01-10 10:00:00');
        $talkB = $this->createTalk('My Talk', 'Bob', 'Different description', '2025-01-10 09:00:00', '2025-01-10 10:00:00');
        $talkC = $this->createTalk('My Talk', 'Alice', 'About stuff', '2025-01-10 10:00:00', '2025-01-10 11:00:00');
        $talkD = $this->createTalk('Other Talk', 'Alice', 'About stuff', '2025-01-10 09:00:00', '2025-01-10 10:00:00');

        $this->assertSame((string) $talkA->getId(), (string) $talkB->getId(), 'Same title should produce identical v5 UUID despite other field differences');
        $this->assertNotSame((string) $talkA->getId(), (string) $talkC->getId(), 'Same title but different time should produce different v5 UUIDs');
        $this->assertNotSame((string) $talkA->getId(), (string) $talkD->getId(), 'Different title and time should produce different v5 UUID');
    }

    public function testMetadata(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Talk::class, DocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement DocumentInterface');
        }

        $talk = $this->createTalk('Opening Keynote', 'Dr. Key Note', 'Welcome to the conference', '2025-01-10 09:00:00', '2025-01-10 10:00:00');

        $expected = [
            'title' => 'Opening Keynote',
            'start_time' => '2025-01-10 09:00:00',
            'end_time' => '2025-01-10 10:00:00',
            'speaker' => 'Dr. Key Note',
            'description' => 'Welcome to the conference',
        ];

        $this->assertSame($expected, $talk->getMetadata()->getArrayCopy(), 'Metadata should contain expected values including talk-specific fields');
    }

    public function testContent(): void
    {
        /* @phpstan-ignore-next-line function.alreadyNarrowedType */
        if (!is_subclass_of(Talk::class, DocumentInterface::class)) {
            $this->markTestSkipped('Event does not implement DocumentInterface');
        }

        $talk = $this->createTalk('Great Talk', 'Jane Doe', 'Deep dive into something great', '2025-01-10 13:00:00', '2025-01-10 14:00:00');

        $expected = <<<EOT
            Title: Great Talk
            Speaker: Jane Doe
            Description: Deep dive into something great
            Time Span: 2025-01-10 13:00:00 to 2025-01-10 14:00:00
            EOT;

        $this->assertSame($expected, $talk->getContent(), 'Content should match expected format with all Talk fields');
    }

    private function createTalk(string $title, string $speaker, string $description, string $start, string $end): Talk
    {
        $timeSpan = new TimeSpan(
            new \DateTimeImmutable($start, new \DateTimeZone('Europe/Warsaw')),
            new \DateTimeImmutable($end, new \DateTimeZone('Europe/Warsaw')),
        );
        $slot = new Slot($timeSpan);

        return new Talk($title, $speaker, $description, $timeSpan, $slot);
    }
}

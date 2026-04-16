<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests\Conference;

use App\Conference\Chat;
use App\Conference\TwigComponent;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\UX\LiveComponent\Test\InteractsWithLiveComponents;

#[CoversClass(TwigComponent::class)]
#[CoversClass(Chat::class)]
final class TwigComponentTest extends WebTestCase
{
    use InteractsWithLiveComponents;

    public function testInitialRenderShowsWelcome(): void
    {
        $component = $this->createLiveComponent('conference', client: static::createClient());

        $crawler = $component->render()->crawler();

        $this->assertCount(1, $crawler->filter('#welcome'));
        $this->assertStringContainsString('SymfonyCon Warsaw 2026', $crawler->filter('#welcome')->text());
    }

    public function testSubmitAddsUserAndAssistantMessage(): void
    {
        $component = $this->createLiveComponent('conference', client: static::createClient());

        $crawler = $component->call('submit', ['message' => 'Hello Warsaw'])->render()->crawler();

        $this->assertCount(0, $crawler->filter('#welcome'));
        $this->assertCount(1, $crawler->filter('#chat-body > .d-flex .bot-message'));
        $this->assertStringContainsString('Hello Warsaw', $crawler->filter('#chat-body')->text());
    }

    public function testResetClearsMessages(): void
    {
        $component = $this->createLiveComponent('conference', client: static::createClient());

        $component->call('submit', ['message' => 'Hello Warsaw']);
        $crawler = $component->call('reset')->render()->crawler();

        $this->assertCount(1, $crawler->filter('#welcome'));
        $this->assertStringNotContainsString('Hello Warsaw', $crawler->filter('#chat-body')->text());
    }
}

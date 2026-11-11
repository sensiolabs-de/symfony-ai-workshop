<?php

namespace App\Command;

use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:openai:chat', 'Chat with OpenAI GPT model')]
final readonly class OpenAiChatCommand
{
    public function __construct(
        private PlatformInterface $platform,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Open AI Chat');

        $message = $io->ask('Enter your message for GPT');

        $messages = new MessageBag(
            Message::forSystem('You are a pirate and you write funny.'),
            Message::ofUser($message),
        );
        $result = $this->platform->invoke('gpt-4o-mini', $messages);

        $io->section('AI Response');
        $io->writeln($result->asText());

        return 0;
    }
}

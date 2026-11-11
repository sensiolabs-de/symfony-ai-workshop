<?php

namespace App\Command;

use Symfony\AI\Agent\AgentInterface;
use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand('app:openai:chat', 'Chat with OpenAI GPT model')]
final readonly class OpenAiChatCommand
{
    public function __construct(
        #[Autowire(service: 'ai.agent.conference')]
        private AgentInterface $agent,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Open AI Chat');
        $messages = new MessageBag();

        while ('bye' !== $userMessage = $io->ask('You')) {
            $messages = $messages->with(Message::ofUser($userMessage));

            $response = $this->agent->call($messages)->asText();

            $io->writeln(' <comment>Bot</comment>:');
            $io->writeln(' > '.$response);

            $messages = $messages->with(Message::ofAssistant($response));
        }

        $io->success('Goodbye!');

        return 0;
    }
}

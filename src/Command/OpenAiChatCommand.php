<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand('app:openai:chat', 'Chat with OpenAI GPT model')]
final readonly class OpenAiChatCommand
{
    public function __construct(
        private HttpClientInterface $httpClient,
        #[\SensitiveParameter]
        #[Autowire('%env(OPENAI_API_KEY)%')]
        private string $openAiApiKey,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $io->title('Open AI Chat');

        $message = $io->ask('Enter your message for GPT');

        $endpoint = 'https://api.openai.com/v1/chat/completions';
        $response = $this->httpClient->request('POST', $endpoint, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => $this->openAiApiKey,
            'json' => [
                'model' => 'gpt-4o-mini',
                'temperature' => 1.0,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a pirate and you write funny.'],
                    ['role' => 'user', 'content' => $message],
                ],
            ],
        ]);

        $data = $response->toArray();
        $reply = $data['choices'][0]['message']['content'] ?? 'No response from AI.';
        $io->section('AI Response');
        $io->writeln($reply);

        return 0;
    }
}

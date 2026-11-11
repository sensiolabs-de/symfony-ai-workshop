<?php

namespace App\Conference\Scraper;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class Client
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    public function getSchedule(): string
    {
        return $this->httpClient
            ->request('GET', 'https://live.symfony.com/2026-warsaw-con/schedule')
            ->getContent();
    }
}

<?php

namespace App\Mcp;

use Mcp\Capability\Attribute\McpTool;
use Symfony\AI\Agent\Bridge\SimilaritySearch\SimilaritySearch;

#[McpTool(
    name: 'search_conference_schedule',
    description: 'Searches the Symfony conference schedule for relevant sessions based on the query.',
)]
final readonly class SearchTool
{
    public function __construct(
        private SimilaritySearch $scheduleSearch,
    ) {
    }

    /**
     * @param string $query The search query
     *
     * @return list<array<string, mixed>>
     */
    public function __invoke(string $query): array
    {
        return ($this->scheduleSearch)($query)->jsonSerialize();
    }
}

<?php

namespace App\DTOs;

class RepositoryInsightDTO
{
    public function __construct(
        public readonly ?int $totalCommits = null,
        public readonly ?array $topContributors = null,
        public readonly ?array $commitFrequency = null,
        public readonly ?int $fileCount = null,
        public readonly ?int $linesOfCode = null,
        public readonly ?float $complexityScore = null,
        public readonly ?float $collaborationScore = null,
        public readonly ?array $topCollaborations = null
    ) {
    }
}

<?php

namespace App\Services\Analysis;

use App\Services\GitRepositoryService;
use App\DTOs\RepositoryInsightDTO;

class CommitAnalysisService
{
    public function __construct(private GitRepositoryService $gitService)
    {
    }

    public function analyze(string $repositoryPath): RepositoryInsightDTO
    {
        $commits = $this->gitService->getCommitHistory($repositoryPath);

        $totalCommits = count($commits);
        $authors = [];
        $commitsByDay = [];

        foreach ($commits as $commit) {
            $authors[$commit['author']] = ($authors[$commit['author']] ?? 0) + 1;
            $day = date('Y-m-d', $commit['timestamp']);
            $commitsByDay[$day] = ($commitsByDay[$day] ?? 0) + 1;
        }

        arsort($authors);
        ksort($commitsByDay);

        return new RepositoryInsightDTO(
            totalCommits: $totalCommits,
            topContributors: array_slice($authors, 0, 5, true),
            commitFrequency: $commitsByDay
        );
    }
}

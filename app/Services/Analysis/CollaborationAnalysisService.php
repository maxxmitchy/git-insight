<?php

namespace App\Services\Analysis;

use App\Services\GitRepositoryService;
use App\DTOs\RepositoryInsightDTO;

class CollaborationAnalysisService
{
    public function __construct(private GitRepositoryService $gitService)
    {
    }

    public function analyze(string $repositoryPath): RepositoryInsightDTO
    {
        $commits = $this->gitService->getCommitHistory($repositoryPath);

        $authorInteractions = [];
        $previousAuthor = null;

        foreach ($commits as $commit) {
            $currentAuthor = $commit['author'];
            if ($previousAuthor && $currentAuthor !== $previousAuthor) {
                $key = $this->getInteractionKey($previousAuthor, $currentAuthor);
                $authorInteractions[$key] = ($authorInteractions[$key] ?? 0) + 1;
            }
            $previousAuthor = $currentAuthor;
        }

        arsort($authorInteractions);

        return new RepositoryInsightDTO(
            collaborationScore: $this->calculateCollaborationScore($authorInteractions),
            topCollaborations: array_slice($authorInteractions, 0, 5, true)
        );
    }

    private function getInteractionKey(string $author1, string $author2): string
    {
        return $author1 < $author2 ? "$author1 - $author2" : "$author2 - $author1";
    }

    private function calculateCollaborationScore(array $authorInteractions): float
    {
        $totalInteractions = array_sum($authorInteractions);
        $uniqueCollaborations = count($authorInteractions);
        return $uniqueCollaborations > 0 ? $totalInteractions / $uniqueCollaborations : 0;
    }
}

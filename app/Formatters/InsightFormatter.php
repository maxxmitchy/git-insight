<?php

namespace App\Formatters;

use App\DTOs\RepositoryInsightDTO;

class InsightFormatter
{
    public function format(array $insights): string
    {
        $output = "Git Repository Insights\n";
        $output .= "========================\n\n";

        $output .= $this->formatCommitInsights($insights['commits']);
        $output .= $this->formatCodeQualityInsights($insights['codeQuality']);
        $output .= $this->formatCollaborationInsights($insights['collaboration']);

        return $output;
    }

    private function formatCommitInsights(RepositoryInsightDTO $insights): string
    {
        $output = "Commit Insights:\n";
        $output .= "-----------------\n";
        $output .= "Total Commits: {$insights->totalCommits}\n\n";

        $output .= "Top Contributors:\n";
        foreach ($insights->topContributors as $author => $count) {
            $output .= "  - $author: $count commits\n";
        }
        $output .= "\n";

        $output .= "Commit Frequency:\n";
        foreach ($insights->commitFrequency as $date => $count) {
            $output .= "  - $date: $count commits\n";
        }
        $output .= "\n";

        return $output;
    }

    private function formatCodeQualityInsights(RepositoryInsightDTO $insights): string
    {
        $output = "Code Quality Insights:\n";
        $output .= "-----------------------\n";
        $output .= "Total Files: {$insights->fileCount}\n";
        $output .= "Total Lines of Code: {$insights->linesOfCode}\n";
        $output .= "Complexity Score: {$insights->complexityScore}\n\n";

        return $output;
    }

    private function formatCollaborationInsights(RepositoryInsightDTO $insights): string
    {
        $output = "Collaboration Insights:\n";
        $output .= "------------------------\n";
        $output .= "Collaboration Score: {$insights->collaborationScore}\n\n";

        $output .= "Top Collaborations:\n";
        foreach ($insights->topCollaborations as $pair => $count) {
            $output .= "  - $pair: $count interactions\n";
        }
        $output .= "\n";

        return $output;
    }
}

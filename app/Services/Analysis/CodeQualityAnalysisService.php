<?php

namespace App\Services\Analysis;

use App\DTOs\RepositoryInsightDTO;
use Symfony\Component\Process\Process;

class CodeQualityAnalysisService
{
    public function analyze(string $repositoryPath): RepositoryInsightDTO
    {
        $fileCount = $this->countFiles($repositoryPath);
        $linesOfCode = $this->countLinesOfCode($repositoryPath);
        $complexityScore = $this->calculateComplexity($repositoryPath);

        return new RepositoryInsightDTO(
            fileCount: $fileCount,
            linesOfCode: $linesOfCode,
            complexityScore: $complexityScore
        );
    }

    private function countFiles(string $path): int
    {
        $process = new Process(['find', $path, '-type', 'f']);
        $process->run();
        return count(explode("\n", trim($process->getOutput())));
    }

    private function countLinesOfCode(string $path): int
    {
        $process = new Process(['find', $path, '-type', 'f', '-exec', 'wc', '-l', '{}', '+']);
        $process->run();
        $output = $process->getOutput();
        preg_match('/(\d+) total/', $output, $matches);
        return (int) ($matches[1] ?? 0);
    }

    private function calculateComplexity(string $path): float
    {
        // This is a simplified complexity calculation
        // In a real-world scenario, you might use tools like PHP Mess Detector or PHP CodeSniffer
        $linesOfCode = $this->countLinesOfCode($path);
        $fileCount = $this->countFiles($path);
        return $fileCount > 0 ? $linesOfCode / $fileCount : 0;
    }
}

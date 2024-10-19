<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Formatters\InsightFormatter;
use Illuminate\Support\Facades\File;
use App\Services\GitRepositoryService;
use Symfony\Component\Process\Process;
use App\Services\Analysis\CommitAnalysisService;
use App\Services\Analysis\CodeQualityAnalysisService;
use App\Services\Analysis\CollaborationAnalysisService;

class GitInsightCommand extends Command
{
    protected $signature = 'git-insight:analyze {path : Path or URL of the Git repository}';
    protected $description = 'Analyze a Git repository and provide insights';

    public function __construct(
        private GitRepositoryService $gitService,
        private CommitAnalysisService $commitAnalysis,
        private CodeQualityAnalysisService $codeQualityAnalysis,
        private CollaborationAnalysisService $collaborationAnalysis,
        private InsightFormatter $formatter
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $path = $this->argument('path');

        try {
            $repositoryPath = $this->getRepositoryPath($path);

            $repository = $this->gitService->openRepository($repositoryPath);

            $commitInsights = $this->commitAnalysis->analyze($repository);
            $codeQualityInsights = $this->codeQualityAnalysis->analyze($repository);
            $collaborationInsights = $this->collaborationAnalysis->analyze($repository);

            $this->output->write($this->formatter->format([
                'commits' => $commitInsights,
                'codeQuality' => $codeQualityInsights,
                'collaboration' => $collaborationInsights,
            ]));

            // Clean up temporary directory if it was created
            if ($repositoryPath !== $path) {
                File::deleteDirectory($repositoryPath);
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("An error occurred: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }

    private function getRepositoryPath(string $path): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $tempDir = sys_get_temp_dir() . '/git-insight-' . uniqid();
            $this->info("Cloning repository to temporary directory: $tempDir");
            $process = new Process(['git', 'clone', $path, $tempDir]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new \RuntimeException("Failed to clone repository: " . $process->getErrorOutput());
            }

            return $tempDir;
        }

        return $path;
    }
}

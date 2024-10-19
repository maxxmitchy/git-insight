<?php

namespace App\Console\Commands;

use App\Services\GitRepositoryService;
use App\Services\Analysis\CommitAnalysisService;
use App\Services\Analysis\CodeQualityAnalysisService;
use App\Services\Analysis\CollaborationAnalysisService;
use App\Formatters\InsightFormatter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class GitInsightCommand extends Command
{
    protected $signature = 'git-insight:analyze {path : Path or URL of the Git repository} {--timeout=300 : Maximum execution time in seconds}';
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
        $timeout = $this->option('timeout');

        try {
            $repositoryPath = $this->getRepositoryPath($path, $timeout);

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

    private function getRepositoryPath(string $path, int $timeout): string
    {
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            $tempDir = sys_get_temp_dir() . '/git-insight-' . uniqid();
            $this->info("Cloning repository to temporary directory: $tempDir");
            $this->info("This may take a while for large repositories...");

            $process = Process::fromShellCommandline("git clone --progress $path $tempDir");
            $process->setTimeout($timeout);

            $process->run(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $this->output->write($buffer);
                }
            });

            if (!$process->isSuccessful()) {
                throw new \RuntimeException("Failed to clone repository: " . $process->getErrorOutput());
            }

            $this->info("Repository cloned successfully.");
            return $tempDir;
        }

        return $path;
    }
}

<?php

namespace App\Services;

use App\Exceptions\GitRepositoryException;
use Symfony\Component\Process\Process;

class GitRepositoryService
{
    public function openRepository(string $path): string
    {
        if (!is_dir($path)) {
            throw new GitRepositoryException("The specified path is not a directory.");
        }

        $process = new Process(['git', '-C', $path, 'rev-parse', '--is-inside-work-tree']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new GitRepositoryException("The specified path is not a Git repository.");
        }

        return $path;
    }

    public function getCommitHistory(string $path): array
    {
        $process = new Process(['git', '-C', $path, 'log', '--pretty=format:%H|%an|%ae|%at|%s']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new GitRepositoryException("Failed to retrieve commit history.");
        }

        $output = $process->getOutput();
        $commits = [];

        foreach (explode("\n", $output) as $line) {
            [$hash, $author, $email, $timestamp, $message] = explode('|', $line, 5);
            $commits[] = [
                'hash' => $hash,
                'author' => $author,
                'email' => $email,
                'timestamp' => $timestamp,
                'message' => $message,
            ];
        }

        return $commits;
    }
}

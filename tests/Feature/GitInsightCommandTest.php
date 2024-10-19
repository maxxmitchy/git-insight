<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class GitInsightCommandTest extends TestCase
{
    public function test_git_insight_command_runs_successfully()
    {
        $exitCode = Artisan::call('git-insight:analyze', ['path' => base_path()]);

        $this->assertEquals(0, $exitCode);
        $this->assertStringContainsString('Git Repository Insights', Artisan::output());
    }

    public function test_git_insight_command_fails_with_invalid_path()
    {
        $exitCode = Artisan::call('git-insight:analyze', ['path' => '/invalid/path']);

        $this->assertEquals(1, $exitCode);
        $this->assertStringContainsString('An error occurred', Artisan::output());
    }
}

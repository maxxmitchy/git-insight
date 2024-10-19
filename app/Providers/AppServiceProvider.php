<?php

namespace App\Providers;

use App\Formatters\InsightFormatter;
use App\Services\GitRepositoryService;
use Illuminate\Support\ServiceProvider;
use App\Services\Analysis\CommitAnalysisService;
use App\Services\Analysis\CodeQualityAnalysisService;
use App\Services\Analysis\CollaborationAnalysisService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(GitRepositoryService::class, function ($app) {
            return new GitRepositoryService();
        });



        $this->app->bind(CommitAnalysisService::class, function ($app) {
            return new CommitAnalysisService($app->make(GitRepositoryService::class));
        });

        $this->app->bind(CodeQualityAnalysisService::class, function ($app) {
            return new CodeQualityAnalysisService();
        });

        $this->app->bind(CollaborationAnalysisService::class, function ($app) {
            return new CollaborationAnalysisService($app->make(GitRepositoryService::class));
        });

        $this->app->bind(InsightFormatter::class, function ($app) {
            return new InsightFormatter();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

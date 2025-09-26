<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\EloquentArticleRepository;
use App\Services\ArticleAggregator;
use App\Services\Providers\NewsApiProvider;
use App\Services\Providers\GuardianProvider;
use App\Services\Providers\NytProvider;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(
            ArticleRepositoryInterface::class,
            EloquentArticleRepository::class
        );

        $this->app->bind(NewsApiProvider::class);
        $this->app->bind(GuardianProvider::class);
        $this->app->bind(NYTProvider::class);

        $this->app->singleton(ArticleAggregator::class, function($app) {
            return new ArticleAggregator([
                $app->make(NewsApiProvider::class),
                $app->make(GuardianProvider::class),
                $app->make(NytProvider::class),
            ], $app->make(ArticleRepositoryInterface::class));
        });
    }

    public function boot(): void {}
}

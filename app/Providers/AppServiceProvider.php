<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Services\Recommendation\Contracts\RecommendationEngineContract;
use App\Services\Recommendation\RecommendationEngine;
use App\Services\SupplyChain\Contracts\SupplyChainRecommendationContract;
use App\Services\SupplyChain\SupplyChainRecommendationEngine;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
{
    $this->app->bind(
        RecommendationEngineContract::class,
        RecommendationEngine::class
    );

    $this->app->bind(
        SupplyChainRecommendationContract::class,
        SupplyChainRecommendationEngine::class
    );
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
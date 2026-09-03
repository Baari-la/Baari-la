<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Services\Recommendation\Contracts\RecommendationEngineContract;
use App\Services\Recommendation\RecommendationEngine;
use App\Services\SupplyChain\Contracts\SupplyChainRecommendationContract;
use App\Services\SupplyChain\SupplyChainRecommendationEngine;
use App\Services\TradeIntelligence\Snapshot\SnapshotAssembler;
use App\Services\TradeIntelligence\Snapshot\SnapshotMetadataBuilder;

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
$this->app->when(
    SnapshotAssembler::class
)->needs(
    SnapshotMetadataBuilder::class
)->give(
    fn () => new SnapshotMetadataBuilder(
        sector: 'garment',
        snapshotKey: 'digestex.trade.sector.garment',
        snapshotType: 'sector',
    )
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
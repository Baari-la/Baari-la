<?php

declare(strict_types=1);

namespace App\Services\Trade\Analytics;

use App\Services\Trade\Metadata\TradeStatisticsMetadataService;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive Analytics Service
 * ==========================================================================
 *
 * Central Business Intelligence Layer.
 */
class ExecutiveAnalyticsService
{
    public function __construct(
        protected TradeAnalyticsService $tradeAnalytics,
        protected CountryAnalyticsService $countryAnalytics,
        protected HSCodeAnalyticsService $hsCodeAnalytics,
        protected EarlyWarningService $earlyWarning,
        protected TradeStatisticsMetadataService $metadataService,
    ) {
    }

    /**
     * Metadata
     */
    public function metadata(): array
    {
        return $this->metadataService->get();
    }

    /**
     * Build Executive Analytics Dataset
     */
    public function build(array $filters = []): array
    {
        return [

            'metadata' => $this->metadata(),

            'summary' => $this->tradeAnalytics->summary($filters),

            'comparison' => $this->tradeAnalytics->monthlyComparison($filters),

            'comparisonPieces' => $this->tradeAnalytics->monthlyComparisonPieces($filters),

            'topCountries' => $this->countryAnalytics->topCountries($filters),

            'topProducts' => $this->hsCodeAnalytics->topProducts($filters),

            'earlyWarnings' => $this->earlyWarning->analyze($filters),

            'executiveSummary' => null,

            'keyFindings' => [],

            'tradeRadar' => [],

            'opportunities' => [],

            'risks' => [],

            'recommendation' => [],
        ];
    }
}
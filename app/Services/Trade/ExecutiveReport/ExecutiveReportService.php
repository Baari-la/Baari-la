<?php

declare(strict_types=1);

namespace App\Services\Trade\ExecutiveReport;

use App\Services\Trade\Analytics\ExecutiveAnalyticsService;

class ExecutiveReportService
{
    public function __construct(
        protected ExecutiveAnalyticsService $analytics,
    ) {
    }

    /**
     * --------------------------------------------------------------------------
     * Build Executive Report
     * --------------------------------------------------------------------------
     */
    public function build(array $filters = []): array
    {
        /*
        |--------------------------------------------------------------------------
        | Executive Analytics
        |--------------------------------------------------------------------------
        */

        $analytics = $this->analytics->build($filters);

        /*
        |--------------------------------------------------------------------------
        | Report Header
        |--------------------------------------------------------------------------
        */

        $header = $this->buildHeader(
            $filters,
            $analytics['metadata'] ?? []
        );

        /*
        |--------------------------------------------------------------------------
        | Executive Report
        |--------------------------------------------------------------------------
        */

        return [

            ...$header,

            /*
            |--------------------------------------------------------------------------
            | Executive Intelligence
            |--------------------------------------------------------------------------
            */

            'summary' => $analytics['summary'] ?? [],

            'comparison' => $analytics['comparison'] ?? [],

            'comparisonPieces' => $analytics['comparisonPieces'] ?? [],

            'topCountries' => $analytics['topCountries'] ?? [],

            'topProducts' => $analytics['topProducts'] ?? [],

            'earlyWarnings' => $analytics['earlyWarnings'] ?? [],

            /*
            |--------------------------------------------------------------------------
            | AI & Strategic Insight
            |--------------------------------------------------------------------------
            */

            'executiveSummary' => '',

            'keyFindings' => [],

            'tradeRadar' => [],

            'opportunities' => [],

            'risks' => [],

            'recommendation' => [],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Build Report Header
     * --------------------------------------------------------------------------
     */
    protected function buildHeader(
        array $filters,
        array $metadata = []
    ): array {

        return [

            'title' => $filters['title']
                ?? 'Indonesia Trade Executive Report',

            'subtitle' => $filters['subtitle']
                ?? 'Digestex Executive Intelligence',

            'reportNumber' => $filters['report_number']
                ?? 'TR-' . now()->format('Ym'),

            'generatedAt' => $metadata['generated_at']
                ?? now()->toDateTimeString(),

            'metadata' => $metadata,

            'country' => $filters['country']
                ?? 'Indonesia',

            'period' => $filters['period']
                ?? ($metadata['latest_period'] ?? null),

            'compare' => $filters['compare']
                ?? null,

        ];
    }
}
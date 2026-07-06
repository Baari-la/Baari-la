<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Executive Summary Service
 * ==========================================================================
 *
 * Converts Executive Intelligence into
 * executive-level business narrative.
 *
 * This service NEVER performs calculations.
 *
 * Data source:
 * - TradeRadarService
 *
 * Used by:
 * - ExecutiveReportService
 * - Dashboard
 * - PDF Report
 * - REST API
 * - AI Executive Summary
 */
class ExecutiveSummaryService
{
    /**
     * --------------------------------------------------------------------------
     * Build Executive Summary
     * --------------------------------------------------------------------------
     */
    public function build(array $tradeRadar): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Executive Narrative
            |--------------------------------------------------------------------------
            */

            'headline' => $this->headline($tradeRadar),

            'summary' => $this->summary($tradeRadar),

            /*
            |--------------------------------------------------------------------------
            | Executive KPI
            |--------------------------------------------------------------------------
            */

            'overallScore' => $tradeRadar['overallScore'] ?? [],

            'marketHealth' => $tradeRadar['status']['marketHealth'] ?? [],

            'kpi' => [

                'overall' => $tradeRadar['overallScore']['score'] ?? null,

                'growth' => $tradeRadar['score']['growth']['score'] ?? null,

                'forecast' => $tradeRadar['score']['forecastConfidence']['score'] ?? null,

            ],

            /*
            |--------------------------------------------------------------------------
            | Executive Signal
            |--------------------------------------------------------------------------
            */

            'executiveSignal' => [

                'trend' => $tradeRadar['status']['marketHealth']['label'] ?? null,

                'risk' => $tradeRadar['status']['riskLevel'] ?? null,

                'opportunity' => $tradeRadar['status']['opportunityLevel'] ?? null,

            ],

            /*
            |--------------------------------------------------------------------------
            | Executive Highlights
            |--------------------------------------------------------------------------
            */

            'highlights' => $this->highlights($tradeRadar),

            /*
            |--------------------------------------------------------------------------
            | Metadata
            |--------------------------------------------------------------------------
            */

            'metadata' => [

                'generated_at' => now()->toDateTimeString(),

                'engine' => 'ExecutiveSummary',

                'version' => '1.0.0',

            ],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Headline
     * --------------------------------------------------------------------------
     */
    protected function headline(array $tradeRadar): string
    {
        $health = $tradeRadar['status']['marketHealth']['label']
            ?? 'Moderate';

        return match ($health) {

            'Excellent' =>
                'Indonesia Trade Performance is Outstanding.',

            'Strong' =>
                'Indonesia Trade Performance Remains Strong.',

            'Healthy' =>
                'Indonesia Trade Performance is Healthy.',

            'Moderate' =>
                'Indonesia Trade Performance is Stable.',

            default =>
                'Indonesia Trade Performance Requires Attention.',

        };
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Summary
     * --------------------------------------------------------------------------
     */
    protected function summary(array $tradeRadar): string
    {
        $status = $tradeRadar['status'];

        $health = strtolower(
            $status['marketHealth']['label'] ?? 'moderate'
        );

        $risk = strtolower(
            $status['riskLevel'] ?? 'medium'
        );

        $opportunity = strtolower(
            $status['opportunityLevel'] ?? 'moderate'
        );

        return sprintf(

            'Overall trade conditions are %s. '
            . 'Current market risk is %s while business opportunities remain %s. '
            . 'The Executive Trade Radar indicates a positive outlook based on the latest trade intelligence.',

            $health,

            $risk,

            $opportunity

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Executive Highlights
     * --------------------------------------------------------------------------
     */
    protected function highlights(array $tradeRadar): array
    {
        $items = [];

        $sections = [

            'growth' => 'Growth',

            'diversification' => 'Diversification',

            'concentration' => 'Concentration',

            'volatility' => 'Volatility',

            'forecastConfidence' => 'Forecast Confidence',

        ];

        foreach ($sections as $key => $title) {

            if (!isset($tradeRadar['score'][$key]['score'])) {
                continue;
            }

            $items[] = [

                'title' => $title,

                'value' => $tradeRadar['score'][$key]['score'],

                'status' => $tradeRadar['score'][$key]['status'] ?? null,

                'grade' => $tradeRadar['score'][$key]['grade'] ?? null,

            ];

        }

        return $items;
    }
}
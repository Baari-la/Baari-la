<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence\Executive;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Early Warning Service
 * ==========================================================================
 *
 * Executive Early Warning Engine.
 *
 * Responsible for:
 *
 * - Detect business risks
 * - Detect declining indicators
 * - Generate executive alerts
 *
 * Data Source:
 *
 * - TradeRadarService
 *
 * This service NEVER performs calculations.
 */
class EarlyWarningService
{
    /**
     * --------------------------------------------------------------------------
     * Warning Thresholds
     * --------------------------------------------------------------------------
     */
    protected const THRESHOLDS = [

        'overall' => 60,

        'growth' => 60,

        'diversification' => 60,

        'concentration' => 80,

        'volatility' => 75,

        'forecast' => 60,

    ];

    /**
     * --------------------------------------------------------------------------
     * Build Early Warnings
     * --------------------------------------------------------------------------
     */
    public function build(array $tradeRadar): array
    {
        $warnings = [];

        $this->checkOverallScore($warnings, $tradeRadar);
        $this->checkGrowth($warnings, $tradeRadar);
        $this->checkDiversification($warnings, $tradeRadar);
        $this->checkConcentration($warnings, $tradeRadar);
        $this->checkVolatility($warnings, $tradeRadar);
        $this->checkForecast($warnings, $tradeRadar);

        usort(
            $warnings,
            fn ($a, $b) => $a['priority'] <=> $b['priority']
        );

        return [

            'warnings' => $warnings,

            'statistics' => $this->statistics($warnings),

            'metadata' => [

                'engine' => 'EarlyWarning',

                'engine_version' => '1.0.0',

                'generated_at' => now()->toDateTimeString(),

            ],

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Overall Score
     * --------------------------------------------------------------------------
     */
    protected function checkOverallScore(array &$warnings, array $tradeRadar): void
    {
        $score = $tradeRadar['overallScore']['score'] ?? 0;

        if ($score >= self::THRESHOLDS['overall']) {
            return;
        }

        $warnings[] = $this->warning(

            id: 'EW001',

            level: 'CRITICAL',

            category: 'Overall Trade',

            title: 'Overall Trade Score is Below Target',

            description: 'Overall trade performance requires immediate executive attention.',

            score: $score,

            priority: 1,

            action: 'Review national export strategy immediately.'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Growth
     * --------------------------------------------------------------------------
     */
    protected function checkGrowth(array &$warnings, array $tradeRadar): void
    {
        $score = $tradeRadar['score']['growth']['score'] ?? 100;

        if ($score >= self::THRESHOLDS['growth']) {
            return;
        }

        $warnings[] = $this->warning(

            id: 'EW002',

            level: 'HIGH',

            category: 'Growth',

            title: 'Growth Momentum Weakening',

            description: 'Growth score has declined below the recommended threshold.',

            score: $score,

            priority: 2,

            action: 'Review export growth initiatives.'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Diversification
     * --------------------------------------------------------------------------
     */
    protected function checkDiversification(array &$warnings, array $tradeRadar): void
    {
        $score = $tradeRadar['score']['diversification']['score'] ?? 100;

        if ($score >= self::THRESHOLDS['diversification']) {
            return;
        }

        $warnings[] = $this->warning(

            id: 'EW003',

            level: 'MEDIUM',

            category: 'Diversification',

            title: 'Export Market Diversification is Low',

            description: 'Export destinations should be diversified to reduce dependency.',

            score: $score,

            priority: 3,

            action: 'Expand into emerging export markets.'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Concentration
     * --------------------------------------------------------------------------
     */
    protected function checkConcentration(array &$warnings, array $tradeRadar): void
    {
        $score = $tradeRadar['score']['concentration']['score'] ?? 0;

        if ($score <= self::THRESHOLDS['concentration']) {
            return;
        }

        $warnings[] = $this->warning(

            id: 'EW004',

            level: 'HIGH',

            category: 'Market Concentration',

            title: 'Export Market Concentration is High',

            description: 'Exports remain concentrated in a limited number of destination markets.',

            score: $score,

            priority: 2,

            action: 'Diversify export destinations.'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Volatility
     * --------------------------------------------------------------------------
     */
    protected function checkVolatility(array &$warnings, array $tradeRadar): void
    {
        $score = $tradeRadar['score']['volatility']['score'] ?? 0;

        if ($score <= self::THRESHOLDS['volatility']) {
            return;
        }

        $warnings[] = $this->warning(

            id: 'EW005',

            level: 'HIGH',

            category: 'Volatility',

            title: 'Trade Volatility Increasing',

            description: 'Trade volatility exceeds the recommended threshold.',

            score: $score,

            priority: 3,

            action: 'Monitor market fluctuations closely.'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Forecast
     * --------------------------------------------------------------------------
     */
    protected function checkForecast(array &$warnings, array $tradeRadar): void
    {
        $score = $tradeRadar['score']['forecastConfidence']['score'] ?? 100;

        if ($score >= self::THRESHOLDS['forecast']) {
            return;
        }

        $warnings[] = $this->warning(

            id: 'EW006',

            level: 'MEDIUM',

            category: 'Forecast',

            title: 'Forecast Confidence Declining',

            description: 'Forecast confidence has fallen below the desired level.',

            score: $score,

            priority: 4,

            action: 'Review market forecast assumptions.'

        );
    }

    /**
     * --------------------------------------------------------------------------
     * Warning Factory
     * --------------------------------------------------------------------------
     */
    protected function warning(
        string $id,
        string $level,
        string $category,
        string $title,
        string $description,
        float|int $score,
        int $priority,
        string $action
    ): array {

        return [

            'id' => $id,

            'level' => $level,

            'priority' => $priority,

            'category' => $category,

            'title' => $title,

            'description' => $description,

            'score' => $score,

            'action' => $action,

            'color' => match ($level) {

                'CRITICAL' => 'red',

                'HIGH' => 'orange',

                'MEDIUM' => 'yellow',

                default => 'blue',

            },

            'icon' => match ($level) {

                'CRITICAL' => 'alert-octagon',

                'HIGH' => 'alert-triangle',

                'MEDIUM' => 'triangle-alert',

                default => 'info',

            },

        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Warning Statistics
     * --------------------------------------------------------------------------
     */
    protected function statistics(array $warnings): array
    {
        return [

            'total' => count($warnings),

            'critical' => count(array_filter(
                $warnings,
                fn ($w) => $w['level'] === 'CRITICAL'
            )),

            'high' => count(array_filter(
                $warnings,
                fn ($w) => $w['level'] === 'HIGH'
            )),

            'medium' => count(array_filter(
                $warnings,
                fn ($w) => $w['level'] === 'MEDIUM'
            )),

        ];
    }
}
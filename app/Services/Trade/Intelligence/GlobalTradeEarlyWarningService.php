<?php

declare(strict_types=1);

namespace App\Services\Trade\Intelligence;

use Illuminate\Support\Collection;

class GlobalTradeEarlyWarningService
{
    private const LOW = 'LOW';

    private const MEDIUM = 'MEDIUM';

    private const HIGH = 'HIGH';

    private const CRITICAL = 'CRITICAL';

    /**
     * --------------------------------------------------------------------------
     * Analyze Global Trade Risks & Opportunities
     * --------------------------------------------------------------------------
     */
    public function analyze(
        array $countries,
        string $dataPeriod = 'January-April 2026',
    ): array {

        $alerts = [];

        foreach ($countries as $country) {

            $this->importSurge(
                $country,
                $alerts
            );

            $this->exportDecline(
                $country,
                $alerts
            );

            $this->newOpportunity(
                $country,
                $alerts
            );

            $this->supplyChainRisk(
                $country,
                $alerts
            );
        }

        $alerts = collect($alerts)

            ->sortByDesc('priority')

            ->values();

        $summary = [

            'critical' => $alerts
                ->where(
                    'severity',
                    self::CRITICAL
                )
                ->count(),

            'high' => $alerts
                ->where(
                    'severity',
                    self::HIGH
                )
                ->count(),

            'medium' => $alerts
                ->where(
                    'severity',
                    self::MEDIUM
                )
                ->count(),

            'low' => $alerts
                ->where(
                    'severity',
                    self::LOW
                )
                ->count(),
        ];

        return [

            'data_period' => $dataPeriod,

            'summary' => $summary,

            'executive_summary' => sprintf(
                'DIGESTEX identified %s critical, %s high, and %s medium priority developments across global textile markets.',
                $summary['critical'],
                $summary['high'],
                $summary['medium'],
            ),

            'alerts' => $alerts->toArray(),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Import Surge
     * --------------------------------------------------------------------------
     */
    protected function importSurge(
        array $country,
        array &$alerts
    ): void {

        $growth = $country['growth'] ?? 0;

        if ($growth < 100) {
            return;
        }

        $severity = $growth >= 200
            ? self::CRITICAL
            : self::HIGH;

        $this->addAlert(
            alerts: $alerts,
            category: 'risk',
            type: 'IMPORT SURGE',
            severity: $severity,
            country: $country['country_name_en'],
            message: sprintf(
                '%s recorded an import surge of %.1f%%.',
                $country['country_name_en'],
                $growth
            ),
            recommendation:
                'Monitor domestic market impact and evaluate diversification strategies.',
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Export Decline
     * --------------------------------------------------------------------------
     */
    protected function exportDecline(
        array $country,
        array &$alerts
    ): void {

        $growth = $country['growth'] ?? 0;

        if ($growth > -20) {
            return;
        }

        $severity = $growth <= -50
            ? self::HIGH
            : self::MEDIUM;

        $this->addAlert(
            alerts: $alerts,
            category: 'risk',
            type: 'EXPORT DECLINE',
            severity: $severity,
            country: $country['country_name_en'],
            message: sprintf(
                '%s experienced an export decline of %.1f%%.',
                $country['country_name_en'],
                abs($growth)
            ),
            recommendation:
                'Review market strategy and strengthen customer engagement.',
        );
    }

    /**
     * --------------------------------------------------------------------------
     * New Opportunity
     * --------------------------------------------------------------------------
     */
    protected function newOpportunity(
        array $country,
        array &$alerts
    ): void {

        $growth = $country['growth'] ?? 0;

        $share = $country['share'] ?? 0;

        $tradeBalance =
            $country['trade_balance'] ?? 0;

        if (
            $growth >= 30 &&
            $share < 10 &&
            $tradeBalance > 0
        ) {

            $this->addAlert(
                alerts: $alerts,
                category: 'opportunity',
                type: 'NEW OPPORTUNITY',
                severity: self::HIGH,
                country: $country['country_name_en'],
                message: sprintf(
                    '%s has emerged as a promising export destination with %.1f%% growth.',
                    $country['country_name_en'],
                    $growth
                ),
                recommendation:
                    'Prioritize this market for business development over the next 12–24 months.',
            );
        }
    }

    /**
     * --------------------------------------------------------------------------
     * Supply Chain Risk
     * --------------------------------------------------------------------------
     */
    protected function supplyChainRisk(
        array $country,
        array &$alerts
    ): void {

        $exportValue =
            $country['export_value'] ?? 0;

        $importValue =
            $country['import_value'] ?? 0;

        if (
            $exportValue <= 0 ||
            $importValue < ($exportValue * 5)
        ) {
            return;
        }

        $this->addAlert(
            alerts: $alerts,
            category: 'risk',
            type: 'SUPPLY CHAIN RISK',
            severity: self::HIGH,
            country: $country['country_name_en'],
            message: sprintf(
                'Indonesia remains highly dependent on imports from %s.',
                $country['country_name_en']
            ),
            recommendation:
                'Consider alternative suppliers to reduce supply chain dependency.',
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Add Alert
     * --------------------------------------------------------------------------
     */
    protected function addAlert(
        array &$alerts,
        string $category,
        string $type,
        string $severity,
        string $country,
        string $message,
        string $recommendation,
    ): void {

        $alerts[] = [

            'category' => $category,

            'type' => $type,

            'severity' => $severity,

            'priority' => match ($severity) {

                self::CRITICAL => 100,
                self::HIGH => 75,
                self::MEDIUM => 50,
                default => 25,
            },

            'country' => $country,

            'message' => $message,

            'recommendation' => $recommendation,
        ];
    }
}
<?php

declare(strict_types=1);

namespace App\Services\Home;

use App\Services\Support\CacheService;
use Illuminate\Support\Facades\DB;

class HomeExecutiveSnapshotService
{
    protected const CACHE_KEY = 'digestex.home.executive-snapshot';

    protected int $ttl = 1800; // 30 minutes

    protected int $baseYear = 2025;

    protected int $currentYear = 2026;

    protected array $months = [1, 2, 3, 4, 5, 6];

    protected int $topLimit = 5;

    /**
     * --------------------------------------------------------------------------
     * Get cached snapshot ONLY
     * --------------------------------------------------------------------------
     *
     * IMPORTANT:
     * This method MUST NEVER build the snapshot during a user request.
     */
    public function getData(): array
    {
        $cached = CacheService::get(
            self::CACHE_KEY
        );

        if (is_array($cached)) {
            return $cached;
        }

        return $this->emptySnapshot();
    }

    /**
     * --------------------------------------------------------------------------
     * Build snapshot
     * --------------------------------------------------------------------------
     *
     * This method is intended for CLI / scheduled warmup.
     * It may perform expensive database aggregation.
     */
    public function build(): array
    {
        $export = $this->buildFlowSnapshot('export');

        $import = $this->buildFlowSnapshot('import');

        return [
            'period' => [
                'base_year' => $this->baseYear,
                'current_year' => $this->currentYear,
                'months' => $this->months,
                'label' =>
                    'H1 '
                    . $this->currentYear
                    . ' vs H1 '
                    . $this->baseYear,
            ],

            'export' => [
                'base_value' => $export['base_value'],
                'current_value' => $export['current_value'],
                'growth' => $export['growth'],
                'top_destination' => $export['top_country'],
                'top_products' => $export['top_products'],
            ],

            'import' => [
                'base_value' => $import['base_value'],
                'current_value' => $import['current_value'],
                'growth' => $import['growth'],
                'top_origin' => $import['top_country'],
                'top_products' => $import['top_products'],
            ],

            'generated_at' => now()->toDateTimeString(),
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Warm / save snapshot
     * --------------------------------------------------------------------------
     */
    public function refresh(): array
    {
        $snapshot = $this->build();

        CacheService::put(
            self::CACHE_KEY,
            $snapshot,
            $this->ttl
        );

        return $snapshot;
    }

    /**
     * --------------------------------------------------------------------------
     * Clear snapshot cache
     * --------------------------------------------------------------------------
     */
    public function forget(): void
    {
        CacheService::forget(self::CACHE_KEY);
    }

    /**
     * --------------------------------------------------------------------------
     * Cache state
     * --------------------------------------------------------------------------
     */
    public function isCached(): bool
    {
        return CacheService::has(
            self::CACHE_KEY
        );
    }

    /**
     * --------------------------------------------------------------------------
     * Empty fallback
     * --------------------------------------------------------------------------
     *
     * This keeps the Home page functional when snapshot has not yet
     * been warmed.
     */
    protected function emptySnapshot(): array
    {
        return [
            'period' => [
                'base_year' => $this->baseYear,
                'current_year' => $this->currentYear,
                'months' => $this->months,
                'label' =>
                    'H1 '
                    . $this->currentYear
                    . ' vs H1 '
                    . $this->baseYear,
            ],

            'export' => [
                'base_value' => 0,
                'current_value' => 0,
                'growth' => null,
                'top_destination' => null,
                'top_products' => [],
            ],

            'import' => [
                'base_value' => 0,
                'current_value' => 0,
                'growth' => null,
                'top_origin' => null,
                'top_products' => [],
            ],

            'generated_at' => null,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Build one flow snapshot
     * --------------------------------------------------------------------------
     */
    protected function buildFlowSnapshot(
        string $tradeFlow
    ): array {

        /*
        |--------------------------------------------------------------------------
        | H1 Aggregate
        |--------------------------------------------------------------------------
        */

        $aggregate = DB::table('trade_statistics')
            ->where('trade_flow', $tradeFlow)
            ->whereIn('year', [
                $this->baseYear,
                $this->currentYear,
            ])
            ->whereIn('month', $this->months)
            ->select('year')
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get()
            ->keyBy('year');

        $baseValue = (float) (
            $aggregate[$this->baseYear]->trade_value
                ?? 0
        );

        $currentValue = (float) (
            $aggregate[$this->currentYear]->trade_value
                ?? 0
        );

        $growth = $this->calculateGrowth(
            $baseValue,
            $currentValue
        );

        /*
        |--------------------------------------------------------------------------
        | Top Country
        |--------------------------------------------------------------------------
        */

        $topCountry = DB::table(
            'trade_statistics as ts'
        )
            ->leftJoin(
                'mst_countries as mc',
                'mc.id',
                '=',
                'ts.country_id'
            )
            ->where(
                'ts.trade_flow',
                $tradeFlow
            )
            ->where(
                'ts.year',
                $this->currentYear
            )
            ->whereIn(
                'ts.month',
                $this->months
            )
            ->whereNotNull('ts.country_id')
            ->where(
                'mc.is_active',
                1
            )
            ->select(
                'ts.country_id',
                'mc.country_code',
                'mc.iso3',
                'mc.country_name_en',
                'mc.country_name_id'
            )
            ->selectRaw(
                'SUM(ts.trade_value) AS trade_value'
            )
            ->groupBy(
                'ts.country_id',
                'mc.country_code',
                'mc.iso3',
                'mc.country_name_en',
                'mc.country_name_id'
            )
            ->orderByDesc(
                'trade_value'
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Top Products
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table(
            'trade_statistics'
        )
            ->where(
                'trade_flow',
                $tradeFlow
            )
            ->where(
                'year',
                $this->currentYear
            )
            ->whereIn(
                'month',
                $this->months
            )
            ->whereNotNull(
                'hs_code'
            )
            ->select(
                'hs_code',
                'hs_description'
            )
            ->selectRaw(
                'SUM(trade_value) AS trade_value'
            )
            ->groupBy(
                'hs_code',
                'hs_description'
            )
            ->orderByDesc(
                'trade_value'
            )
            ->limit(
                $this->topLimit
            )
            ->get()
            ->map(
                fn ($row): array => [
                    'hs_code' =>
                        $row->hs_code,

                    'product' =>
                        $row->hs_description,

                    'trade_value' =>
                        (float) $row->trade_value,
                ]
            )
            ->values()
            ->all();

        return [
            'base_value' => $baseValue,
            'current_value' => $currentValue,
            'growth' => $growth,

            'top_country' => $topCountry
                ? [
                    'country_id' =>
                        (int) $topCountry->country_id,

                    'country_code' =>
                        $topCountry->country_code,

                    'iso3' =>
                        $topCountry->iso3,

                    'country_name_en' =>
                        $topCountry->country_name_en,

                    'country_name_id' =>
                        $topCountry->country_name_id,

                    'trade_value' =>
                        (float) $topCountry->trade_value,
                ]
                : null,

            'top_products' =>
                $topProducts,
        ];
    }

    /**
     * --------------------------------------------------------------------------
     * Growth
     * --------------------------------------------------------------------------
     */
    protected function calculateGrowth(
        float $baseValue,
        float $currentValue
    ): ?float {

        if ($baseValue <= 0) {
            return null;
        }

        return round(
            (
                ($currentValue - $baseValue)
                / $baseValue
            ) * 100,
            2
        );
    }
}
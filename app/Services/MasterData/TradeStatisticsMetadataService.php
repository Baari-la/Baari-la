<?php

namespace App\Services\Trade\Metadata;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TradeStatisticsMetadataService
{
    protected const CACHE_KEY = 'trade_statistics_metadata';

    protected const CACHE_TTL = 21600; // 6 jam

    /**
     * Get Trade Metadata
     */
    public function get(): array
    {
        return Cache::remember(
            self::CACHE_KEY,
            self::CACHE_TTL,
            fn () => $this->build()
        );
    }

    /**
     * Rebuild Metadata
     */
    public function refresh(): array
    {
        Cache::forget(self::CACHE_KEY);

        return $this->get();
    }

    /**
     * Build Metadata
     */
    protected function build(): array
    {
        /*
        |--------------------------------------------------------------------------
        | Trade Statistics Metadata
        |--------------------------------------------------------------------------
        |
        | trade_statistics remains the source for:
        | - reporting period
        | - trade records
        | - countries
        | - trade value
        | - HS codes actually present in trade data
        |
        */

        $tradeQuery = DB::table('trade_statistics')
            ->where('trade_flow', 'export');

        /*
        |--------------------------------------------------------------------------
        | Canonical HS-8 Master
        |--------------------------------------------------------------------------
        |
        | mst_hscode is the authoritative textile HS-8 universe.
        |
        */

        $canonicalHs8Count = DB::table('mst_hscode')
            ->where('is_active', true)
            ->where('is_textile', true)
            ->count();

        return [

            /*
            |--------------------------------------------------------------------------
            | Trade Period
            |--------------------------------------------------------------------------
            */

            'latest_year' =>
                (clone $tradeQuery)
                    ->max('year'),

            'oldest_year' =>
                (clone $tradeQuery)
                    ->min('year'),

            /*
            |--------------------------------------------------------------------------
            | Data Freshness
            |--------------------------------------------------------------------------
            */

            'last_updated' =>
                (clone $tradeQuery)
                    ->max('updated_at'),

            /*
            |--------------------------------------------------------------------------
            | Trade Dataset
            |--------------------------------------------------------------------------
            */

            'total_records' =>
                (clone $tradeQuery)
                    ->count(),

            /*
            |--------------------------------------------------------------------------
            | HS Codes Actually Present in Trade Data
            |--------------------------------------------------------------------------
            |
            | This is intentionally NOT the Canonical HS-8 count.
            |
            */

            'total_hs_codes' =>
                (clone $tradeQuery)
                    ->distinct('hs_code')
                    ->count('hs_code'),

            /*
            |--------------------------------------------------------------------------
            | Canonical Textile HS-8 Universe
            |--------------------------------------------------------------------------
            */

            'canonical_hs8_count' =>
                $canonicalHs8Count,

            /*
            |--------------------------------------------------------------------------
            | Countries
            |--------------------------------------------------------------------------
            */

            'total_countries' =>
                (clone $tradeQuery)
                    ->whereNotNull('country_code')
                    ->distinct('country_code')
                    ->count('country_code'),

            /*
            |--------------------------------------------------------------------------
            | Export Value
            |--------------------------------------------------------------------------
            */

            'total_export_value' =>
                (clone $tradeQuery)
                    ->sum('trade_value'),
        ];
    }
}
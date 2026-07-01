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
        return [

            'latest_year' => DB::table('trade_statistics')
                ->where('trade_flow', 'export')
                ->max('year'),

            'oldest_year' => DB::table('trade_statistics')
                ->where('trade_flow', 'export')
                ->min('year'),

            'last_updated' => DB::table('trade_statistics')
                ->where('trade_flow', 'export')
                ->max('updated_at'),

            'total_records' => DB::table('trade_statistics')
                ->where('trade_flow', 'export')
                ->count(),

            'total_hs_codes' => DB::table('trade_statistics')
                ->where('trade_flow', 'export')
                ->distinct('hs_code')
                ->count('hs_code'),

            'total_countries' => DB::table('trade_statistics')
                ->where('trade_flow', 'export')
                ->distinct('country_code')
                ->count('country_code'),

            'total_export_value' => DB::table('trade_statistics')
                ->where('trade_flow', 'export')
                ->sum('trade_value'),

        ];
    }
}
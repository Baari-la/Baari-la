<?php

declare(strict_types=1);

namespace App\Services\Dashboard;

use App\Models\MarketHistory;
use Illuminate\Support\Facades\DB;

/**
 * ==========================================================================
 * DIGESTEX CORE
 * ==========================================================================
 * Market History Service
 * ==========================================================================
 *
 * Business service for Market Intelligence.
 *
 * Responsible for:
 *
 * - Latest Cotton Price
 * - Exchange Rate
 * - Market History
 * - Top Stock Snapshot
 *
 * No caching is performed here.
 */
class MarketHistoryService
{
    /**
     * --------------------------------------------------------------------------
     * Market Dataset
     * --------------------------------------------------------------------------
     */
    public function get(): array
    {
        $latest = MarketHistory::query()
            ->latest('date')
            ->first();

        $history = MarketHistory::query()
            ->latest('date')
            ->take(7)
            ->get()
            ->reverse()
            ->values()
            ->map(function ($item) {

                return [

                    'month' => date(
                        'd M',
                        strtotime($item->date)
                    ),

                    'price' => (float) str_replace(
                        ',',
                        '',
                        $item->cotton_price
                    ),

                    'exchange' => (float) $item->usd_idr,

                ];

            });

        $topStocks = DB::table('companies')

            ->where('stock_qty', '>', 0)

            ->selectRaw("
                id as company_id,
                stock_ready_caption as product_name,
                SUM(stock_qty) as total_qty,
                stock_unit as unit
            ")

            ->groupBy(
                'company_id',
                'product_name',
                'unit'
            )

            ->orderByDesc('total_qty')

            ->limit(10)

            ->get();

        return [

            'currentCotton' => $latest->cotton_price ?? 71.31,

            'currentExchange' => $latest->usd_idr ?? 16000,

            'marketHistory' => $history,

            'topStocks' => $topStocks,

            'generated_at' => now()->toDateTimeString(),

        ];
    }
}
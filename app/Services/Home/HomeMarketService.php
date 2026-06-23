<?php

namespace App\Services\Home;

use App\Models\MarketHistory;

class HomeMarketService
{
    public function getData(): array
    {
        $latestMarket = MarketHistory::orderBy('date', 'desc')->first();

        $marketHistory = MarketHistory::orderBy('date', 'desc')
            ->take(7)
            ->get()
            ->reverse()
            ->values()
            ->map(fn($item) => [
                'month'    => date('d M', strtotime($item->date)),
                'price'    => (float) str_replace(',', '', $item->cotton_price), 
                'exchange' => (float) $item->usd_idr,
            ]);

        $topStocks = \DB::table('companies')
            ->where('stock_qty', '>', 0)
            ->selectRaw('
                id as company_id, 
                stock_ready_caption as product_name, 
                SUM(stock_qty) as total_qty, 
                stock_unit as unit
            ')
            ->groupBy('company_id', 'product_name', 'unit')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get();

        return [
            'currentCotton'   => $latestMarket->cotton_price ?? 71.31,
            'currentExchange' => $latestMarket->usd_idr ?? 16000,
            'marketHistory'   => $marketHistory,
            'topStocks'       => $topStocks,
        ];
    }
}
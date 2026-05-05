<?php
namespace App\Http\Controllers;

use App\Models\MarketHistory;
use Inertia\Inertia;

class MarketIntelligenceController extends Controller
{
    public function getHomeData()
    {
        // Mengambil data 30 hari terakhir untuk grafik
        $history = MarketHistory::orderBy('date', 'asc')->take(30)->get();

        return Inertia::render('Home', [
            'marketHistory' => $history,
            'currentCotton' => $history->last()->cotton_price ?? 71.31,
            'currentExchange' => $history->last()->usd_idr ?? 16025,
        ]);
    }
}
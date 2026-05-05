<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MarketHistory;
use Carbon\Carbon;

class MarketHistorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [];
        $cottonBase = 71.31;
        $idrBase = 16025;

        for ($i = 30; $i >= 0; $i--) {
            $data[] = [
                'date' => Carbon::now()->subDays($i)->format('Y-m-d'),
                'cotton_price' => $cottonBase + (rand(-100, 100) / 100), // Fluktuasi +- 1.00
                'usd_idr' => $idrBase + rand(-50, 50), // Fluktuasi +- 50 perak
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        MarketHistory::insert($data);
    }
}
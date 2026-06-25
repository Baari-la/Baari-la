<?php

namespace App\Services\Home;

use Illuminate\Support\Facades\DB;

class HomeTradeService
{
    public function getData(): array
    {
        $garmentTradeData = DB::table('trade_master_annual_hscode')
            ->selectRaw("
                SUM(CASE WHEN tipe_arus = 'ekspor' THEN 
                    CASE 
                        WHEN TRIM(hs_code) LIKE '6109%' THEN vol_2025 * 5.5
                        WHEN TRIM(hs_code) LIKE '6110%' THEN vol_2025 * 2.5
                        WHEN TRIM(hs_code) LIKE '6203%' OR TRIM(hs_code) LIKE '6204%' THEN vol_2025 * 1.8
                        WHEN TRIM(hs_code) LIKE '6111%' OR TRIM(hs_code) LIKE '6209%' THEN vol_2025 * 8.0
                        ELSE vol_2025 * 4.0
                    END ELSE 0 END) as export_pcs,
                SUM(CASE WHEN tipe_arus = 'impor' THEN 
                    CASE 
                        WHEN TRIM(hs_code) LIKE '6109%' THEN vol_2025 * 5.5
                        ELSE vol_2025 * 4.0
                    END ELSE 0 END) as import_pcs
            ")
            ->whereRaw("(TRIM(hs_code) LIKE '61%' OR TRIM(hs_code) LIKE '62%')")
            ->first();
$topProducts = DB::table('trade_master_annual_hscode')
            ->selectRaw("
                TRIM(hs_code) as hs_code_clean, 
                uraian_hs, 
                val_2025,
                -- 🌟 Konversi Volume dari Kg ke Pcs sesuai rumpun HS Code
                CASE 
                    WHEN TRIM(hs_code) LIKE '6109%' THEN vol_2025 * 5.5
                    WHEN TRIM(hs_code) LIKE '6110%' THEN vol_2025 * 2.5
                    WHEN TRIM(hs_code) LIKE '6203%' OR TRIM(hs_code) LIKE '6204%' THEN vol_2025 * 1.8
                    WHEN TRIM(hs_code) LIKE '6111%' OR TRIM(hs_code) LIKE '6209%' THEN vol_2025 * 8.0
                    ELSE vol_2025 * 4.0
                END as vol_2025,
                -- Menghitung growth berdasarkan Value USD
                CASE 
                    WHEN val_2024 > 0 THEN ((val_2025 - val_2024) / val_2024) * 100 
                    ELSE 0 
                END as growth
            ")
            ->where('tipe_arus', 'ekspor')
            ->where(function($q) {
                $q->whereRaw("TRIM(hs_code) LIKE '61%'")->orWhereRaw("TRIM(hs_code) LIKE '62%'");
            })
            ->orderBy('val_2025', 'desc')
            // ->take(15)
            ->get();

        // Fiber Intelligence dengan proteksi autentikasi publik
        $fiberData = $this->getRawFiberData();
        if (!auth()->check()) {
            $fiberData = collect($fiberData)->map(function($item, $key) {
                if ($key > 3) { 
                    $item['cotton_vol'] = 0; $item['cotton_val'] = 0;
                    $item['syn_vol'] = 0;    $item['syn_val'] = 0;
                }
                return $item;
            })->all();
        }

        return [
            'garmentTrade'      => $garmentTradeData,
            'totalGarment'      => (float) ($garmentTradeData->export_pcs ?? 0),
            'topProducts'       => $topProducts,
            'fiberIntelligence' => $fiberData,
        ];
    }

    private function getRawFiberData(): array
    {
        return [
            ['year' => '2019', 'cotton_vol' => 320000, 'cotton_val' => 540000000, 'syn_vol' => 450000, 'syn_val' => 620000000],
            ['year' => '2020', 'cotton_vol' => 290000, 'cotton_val' => 480000000, 'syn_vol' => 410000, 'syn_val' => 580000000],
            ['year' => '2021', 'cotton_vol' => 340000, 'cotton_val' => 610000000, 'syn_vol' => 480000, 'syn_val' => 690000000],
            ['year' => '2022', 'cotton_vol' => 310000, 'cotton_val' => 590000000, 'syn_vol' => 460000, 'syn_val' => 660000000],
            ['year' => '2023', 'cotton_vol' => 330000, 'cotton_val' => 620000000, 'syn_vol' => 490000, 'syn_val' => 710000000],
            ['year' => '2024', 'cotton_vol' => 350000, 'cotton_val' => 650000000, 'syn_vol' => 520000, 'syn_val' => 750000000],
            ['year' => '2025', 'cotton_vol' => 370000, 'cotton_val' => 690000000, 'syn_vol' => 550000, 'syn_val' => 800000000],
        ];
    }
}
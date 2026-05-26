<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PortTrackerController extends Controller
{
    public function storeFeedData(Request $request)
    {
        try {
            // Ambil semua data kiriman mentah dari Python secara elastis
            $data = $request->all();

            // 🔥 EKSEKUSI PENEMBAK LANGSUNG: Mengunci koneksi murni 'mysql' agar tidak tersesat ke database lain
       \Illuminate\Support\Facades\DB::connection('mysql')->table('port_container_logs')->insert([
            'container_no'        => $data['container_no'] ?? 'UNKNOWN-CONT',
            'logistics_date'      => $data['logistics_date'] ?? now()->strftime("%Y-%m-%d"),
            'port_name'           => $data['port_name'] ?? 'DEFAULT-PORT',
            'country_origin'      => $data['country_origin'] ?? '-',
            'country_destination' => $data['country_destination'] ?? '-',
            'hs_code'             => $data['hs_code'] ?? '6203',
            'commodity_type'      => $data['commodity_type'] ?? 'Textile Apparel Cargo',
            'volume_unit'         => $data['volume_unit'] ?? 'PCS',
            'quantity'            => isset($data['quantity']) ? (int)$data['quantity'] : 0,
            'gate_status'         => $data['gate_status'] ?? 'GATE-IN FULL',
            'created_at'          => $data['timestamp'] ?? now(),
            'updated_at'          => now()
        ]);

            return response()->json(['status' => 'Data Permanently Written to MySQL Table'], 201);

        } catch (\Exception $e) {
            // Jika database macet kembali, Laravel akan membisikkan pesan eror aslinya ke log sistem
            return response()->json([
                'error' => 'Database Query Stalled',
                'message' => $e->getMessage()
            ], 500);
        }
    }

      public function getLiveEwsStatus()
    {
        
        // 🌟 KUNCI WAKTU DINAMIS: Mengambil bulan dan tahun aktif hari ini dari jam komputer Bapak
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // 1. Hitung kontainer impor Benang khusus BULAN BERJALAN INI SAJA
        $realPolyesterCount = \Illuminate\Support\Facades\DB::connection('mysql')
            ->table('port_container_logs')
            ->where('hs_code', '6203')
            ->whereMonth('created_at', $currentMonth) // Filter Bulan Ini
            ->whereYear('created_at', $currentYear)   // Filter Tahun Ini
            ->where(function($query) {
                $query->where('gate_status', 'like', '%IMPOR%')
                      ->orWhere('gate_status', 'like', '%OUTFLOW%');
            })
            ->count();

        // 2. Hitung kontainer impor Kain khusus BULAN BERJALAN INI SAJA
        $realCottonFabricCount = \Illuminate\Support\Facades\DB::connection('mysql')
            ->table('port_container_logs')
            ->where('hs_code', '6204')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where(function($query) {
                $query->where('gate_status', 'like', '%IMPOR%')
                      ->orWhere('gate_status', 'like', '%OUTFLOW%');
            })
            ->count();

        // 3. Matriks keputusan alarm cerdas EWS
        // Parameter Ambang Batas Bahaya Bulanan (Monthly Danger Threshold Base)
        $polyesterThreshold = 100; // Contoh standardisasi kapasitas 100 kontainer/bulan
        $cottonThreshold = 90;

        return response()->json([
            [
                "commodity" => "Polyester Filament Yarn (Benang Sintetis)",
                "hs" => "5402",
                "containers" => $realPolyesterCount,
                "threshold" => $polyesterThreshold,
                "risk" => $realPolyesterCount >= $polyesterThreshold ? "CRITICAL" : "SAFE",
                "days" => 45,
                "impact" => "Pasar Domestik dideteksi sedang menerima limpahan Benang Polyester impor. Server mendeteksi " . $realPolyesterCount . " kontainer aktif bergerak ke gudang distributor hulu."
            ],
            [
                "commodity" => "Woven Cotton Fabrics (Kain Tenun Katun)",
                "hs" => "5208",
                "containers" => $realCottonFabricCount,
                "threshold" => $cottonThreshold,
                "risk" => $realCottonFabricCount >= $cottonThreshold ? "WARNING" : "SAFE",
                "days" => 60,
                "impact" => "Laju impor kain katun via Tanjung Priok terpantau konstan di angka " . $realCottonFabricCount . " kontainer. Disarankan pabrik tenun membatasi output weaving."
            ]
        ]);
    }
}